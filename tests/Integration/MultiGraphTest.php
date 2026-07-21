<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Integration;

use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\PaymentDto;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class MultiGraphTest extends TestCase
{
    #[Test]
    public function one_subject_can_host_independent_graphs(): void
    {
        $paymentConfig = [
            'property' => 'paymentStatus',
            'states' => ['unpaid', 'paid', 'refunded'],
            'initial_state' => 'unpaid',
            'transitions' => [
                'pay' => ['from' => ['unpaid'], 'to' => 'paid'],
                'refund' => ['from' => ['paid'], 'to' => 'refunded'],
            ],
        ];

        $fulfillmentConfig = [
            'property' => 'fulfillmentStatus',
            'states' => ['unfulfilled', 'fulfilled'],
            'initial_state' => 'unfulfilled',
            'transitions' => [
                'fulfill' => ['from' => ['unfulfilled'], 'to' => 'fulfilled'],
            ],
        ];

        $subject = new PaymentDto;
        $factory = new StateMachineFactory;

        $payment = $factory->create($subject, 'payment', $paymentConfig);
        $fulfillment = $factory->create($subject, 'fulfillment', $fulfillmentConfig);

        $payment->apply('pay');
        $fulfillment->apply('fulfill');

        $this->assertSame('paid', $subject->paymentStatus);
        $this->assertSame('fulfilled', $subject->fulfillmentStatus);
        $this->assertSame('paid', $payment->getState());
        $this->assertSame('fulfilled', $fulfillment->getState());
    }
}
