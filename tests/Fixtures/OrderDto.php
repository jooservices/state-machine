<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures;

/**
 * Simple test subject with a public status property.
 */
class OrderDto
{
    public function __construct(
        public string $status = 'pending',
        public string $id = 'order-1',
    ) {}
}
