<?php

namespace Tests\CircuitBreakerBundle\Event;

use CircuitBreakerBundle\Event\CircuitBreakerEvent;

class CircuitBreakerEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        $circuitBreakerEvent = new CircuitBreakerEvent('test', false);
        $this->assertEquals('test', $circuitBreakerEvent->getKey());
    }

    public function testGetStatus()
    {
        $circuitBreakerEvent = new CircuitBreakerEvent('test', false);
        $this->assertEquals(false, $circuitBreakerEvent->getStatus());
    }
}
