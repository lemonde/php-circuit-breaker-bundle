<?php

namespace Tests\CircuitBreakerBundle\Traits;

use CircuitBreakerBundle\Event\CircuitBreakerEvent;
use CircuitBreakerBundle\Service\CircuitBreakerService;
use CircuitBreakerBundle\Traits\CircuitBreakerAwareTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CircuitBreakerAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    private $eventDispatcher;
    private $circuitBreaker;

    protected function setUp()
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->circuitBreaker = $this->createMock(CircuitBreakerService::class);
    }


    public function testSetCircuitBreaker()
    {
        $trait = $this->getMockForTrait(CircuitBreakerAwareTrait::class);

        $trait->setCircuitBreaker($this->circuitBreaker);

        $this->assertSame(
            $this->circuitBreaker,
            \PHPUnit_Framework_Assert::readAttribute($trait, 'circuitBreaker')
        );
    }

    public function testSetEventDispatcher()
    {
        $trait = $this->getMockForTrait(CircuitBreakerAwareTrait::class);

        $trait->setEventDispatcher($this->eventDispatcher);

        $this->assertSame(
            $this->eventDispatcher,
            \PHPUnit_Framework_Assert::readAttribute($trait, 'eventDispatcher')
        );
    }

    public function testNotifyServiceIsDown()
    {
        $trait = $this->getMockForTrait(CircuitBreakerAwareTrait::class);
        $trait->setEventDispatcher($this->eventDispatcher);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('circuit.breaker', $this->callback(function ($event) {
                return $event instanceof CircuitBreakerEvent
                    && $event->getKey() === 'test_service'
                    && $event->getStatus() === CircuitBreakerService::STATUS_DOWN;
            }));

        $trait->notifyServiceIsDown('test_service');
    }

    public function testNotifyServiceIsUp()
    {
        $trait = $this->getMockForTrait(CircuitBreakerAwareTrait::class);
        $trait->setEventDispatcher($this->eventDispatcher);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('circuit.breaker', $this->callback(function ($event) {
                return $event instanceof CircuitBreakerEvent
                    && $event->getKey() === 'test_service'
                    && $event->getStatus() === CircuitBreakerService::STATUS_UP;
            }));

        $trait->notifyServiceIsUp('test_service');
    }
}
