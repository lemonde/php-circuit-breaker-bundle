<?php declare(strict_types = 1);

namespace CircuitBreakerBundle\Traits;

use CircuitBreakerBundle\Service\CircuitBreakerService;
use CircuitBreakerBundle\Event\CircuitBreakerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait CircuitBreakerAwareTrait
{
    /**
     * @var CircuitBreakerService
     */
    private $circuitBreaker;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Set the circuit breaker service
     *
     * @param CircuitBreakerService $circuitBreaker
     */
    public function setCircuitBreaker(CircuitBreakerService $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * Send an event to notify the circuit breaker that a given service is down
     *
     * @param string $serviceName The service which is down
     */
    public function notifyServiceIsDown(string $serviceName)
    {
        $this->eventDispatcher->dispatch(
            'circuit.breaker',
            new CircuitBreakerEvent($serviceName, CircuitBreakerService::STATUS_DOWN)
        );
    }

    /**
     * Send an event to notify the circuit breaker that a given service is up
     *
     * @param string $serviceName The service which is up
     */
    public function notifyServiceIsUp(string $serviceName)
    {
        $this->eventDispatcher->dispatch(
            'circuit.breaker',
            new CircuitBreakerEvent($serviceName, CircuitBreakerService::STATUS_UP)
        );
    }
}
