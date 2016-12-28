<?php declare(strict_types = 1);

namespace CircuitBreakerBundle\Listener;

use CircuitBreakerBundle\Event\CircuitBreakerEvent;
use CircuitBreakerBundle\Service\CircuitBreakerService;

class CircuitBreakerListener
{
    /**
     * @var CircuitBreakerService
     */
    private $circuitBreaker;

    /**
     * @param CircuitBreakerService $circuitBreaker
     */
    public function __construct(CircuitBreakerService $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
    }

    /**
     * @param CircuitBreakerEvent $event
     */
    public function onCircuitBreaker(CircuitBreakerEvent $event)
    {
        $this->circuitBreaker->save($event->getKey(), $event->getStatus());
    }
}
