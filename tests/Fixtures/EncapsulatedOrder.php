<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures;

/**
 * Subject that exposes state only through getter/setter methods.
 */
class EncapsulatedOrder
{
    private string $status;

    public function __construct(string $status = 'pending')
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
