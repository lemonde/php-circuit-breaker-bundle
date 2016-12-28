<?php

namespace Tests\CircuitBreakerBundle\Listener;

use CircuitBreakerBundle\Listener\CircuitBreakerListener;

class CircuitBreakerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnCircuitBreaker()
    {
        $this->mockCircuitBreakerEvent = $this->getMockBuilder('CircuitBreakerBundle\Event\CircuitBreakerEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCircuitBreakerEvent
            ->method('getKey')
            ->will($this->returnValue('test'));
        $this->mockCircuitBreakerEvent
            ->method('getStatus')
            ->will($this->returnValue(false));

        $this->mockCircuitBreakerService = $this->getMockBuilder('CircuitBreakerBundle\Service\CircuitBreakerService')
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();

        $this->mockCircuitBreakerService->expects($this->once())
            ->method('save')
            ->with('test', false);

        $circuitBreakerListener = new CircuitBreakerListener($this->mockCircuitBreakerService);
        $circuitBreakerListener->onCircuitBreaker($this->mockCircuitBreakerEvent);
    }
}
