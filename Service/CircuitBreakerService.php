<?php declare(strict_types = 1);

namespace CircuitBreakerBundle\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait as PsrLoggerTrait;
use Symfony\Component\Cache\Adapter\AbstractAdapter;

class CircuitBreakerService
{
    use PsrLoggerTrait;

    const OPEN = 'open';
    const CLOSED = 'closed';
    const HALFOPEN = 'half-open';

    /**
     * @var AbstractAdapter
     */
    private $cacheApp;

    /**
     * @var array
     */
    private $status;

    /**
     * @var int
     */
    private $threshold;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @param AbstractAdapter $cacheApp
     * @param int $threshold
     * @param int $timeout
     */
    public function __construct(AbstractAdapter $cacheApp, int $threshold, int $timeout)
    {
        $this->cacheApp = $cacheApp;
        $this->threshold = $threshold;
        $this->timeout = $timeout;
    }

    /**
     * @param string $key The service key
     * @param bool $status The service status (true: up, false: down)
     */
    public function save(string $key, bool $status)
    {
        if (!isset($this->status[$key])) {
            $this->status[$key] = self::CLOSED;
        }

        if ($this->status[$key] === self::OPEN) {
            $this->attemptReset($key);
        }

        if (!$status) {
            $this->countFailure($key);
        } else {
            $this->resetCount($key);
        }
    }

    /**
     * Verify if service is open.
     *
     * @param string $service
     *
     * @return bool
     */
    public function isOpen(string $service): bool
    {
        if (!isset($this->status[$service]) &&
            $this->cacheApp->getItem($service)->get() >= $this->threshold
        ) {
            $this->status[$service] = self::OPEN;
        }

        return $this->status[$service] === self::OPEN;
    }

    /**
     * Increment number of fail to one service
     *
     * @param string $service
     */
    private function countFailure(string $service)
    {
        $this->notice('[CircuitBreaker] call countFailure to ' . $service);
        $value = $this->cacheApp->getItem($service);
        $fail = $value->get() + 1;
        $value->set($fail);

        if ($this->status[$service] === self::HALFOPEN) {
            $value->set($this->threshold);
        }

        $value->expiresAfter($this->timeout);

        if ($fail >= $this->threshold) {
            $this->tripBreaker($service);
        }

        $this->cacheApp->save($value);
    }

    /**
     * Open circuit breaker.
     *
     * @param string $service
     */
    private function tripBreaker(string $service)
    {
        $this->error('[CircuitBreaker] call tripBreaker to ' . $service);
        $this->status[$service] = self::OPEN;
    }

    /**
     * CLose circuit breaker, and reset value to fail service.
     *
     * @param string $service
     */
    private function resetCount(string $service)
    {
        $this->info('[CircuitBreaker] call resetCount to ' . $service);
        $value = $this->cacheApp->getItem($service);

        $value->set(0);
        $this->status[$service] = self::CLOSED;
        $this->cacheApp->save($value);
    }

    /**
     * HalfOpen circuit breaker.
     *
     * @param string $service
     */
    private function attemptReset(string $service)
    {
        $this->warning('[CircuitBreaker] call attemptReset to ' . $service);
        $this->status[$service] = self::HALFOPEN;
    }

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->logger->log($level, $message, $context);
    }
}
