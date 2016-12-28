<?php declare(strict_types=1);

namespace CircuitBreakerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CircuitBreakerEvent extends Event
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var bool
     */
    private $status;

    /**
     * @param string $key
     * @param bool $status
     */
    public function __construct(string $key, bool $status)
    {
        $this->key = $key;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }
}
