<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures;

/**
 * Test subject with two independent state properties for multi-graph tests.
 */
class PaymentDto
{
    public function __construct(
        public string $paymentStatus = 'unpaid',
        public string $fulfillmentStatus = 'unfulfilled',
        public string $id = 'payment-1',
    ) {}
}
