<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit;

use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\TestCase;
use JOOservices\StateMachine\TransitionContext;
use PHPUnit\Framework\Attributes\Test;

final class TransitionContextTest extends TestCase
{
    #[Test]
    public function it_exposes_immutable_transition_data(): void
    {
        $subject = new OrderDto;
        $context = new TransitionContext(
            subject: $subject,
            transition: 'confirm',
            from: 'pending',
            to: 'confirmed',
            metadata: ['actor' => 'admin'],
        );

        $this->assertSame($subject, $context->subject);
        $this->assertSame('confirm', $context->transition);
        $this->assertSame('pending', $context->from);
        $this->assertSame('confirmed', $context->to);
        $this->assertSame(['actor' => 'admin'], $context->metadata);
    }
}
