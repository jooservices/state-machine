<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit;

use JOOservices\StateMachine\Accessors\PropertyAccessor;
use JOOservices\StateMachine\Config\StateMachineConfig;
use JOOservices\StateMachine\Exceptions\InvalidTransitionException;
use JOOservices\StateMachine\StateMachine;
use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\Fixtures\UninitializedStatusSubject;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class StateMachineTest extends TestCase
{
    private function machine(OrderDto $order = new OrderDto): StateMachine
    {
        return new StateMachine(
            subject: $order,
            config: StateMachineConfig::fromArray('order', $this->orderGraphConfig()),
            accessor: new PropertyAccessor,
        );
    }

    #[Test]
    public function it_reports_current_state_and_available_transitions(): void
    {
        $machine = $this->machine();

        $this->assertSame('pending', $machine->getState());
        $this->assertTrue($machine->can('confirm'));
        $this->assertTrue($machine->can('cancel'));
        $this->assertFalse($machine->can('ship'));
        $this->assertFalse($machine->can('missing'));
        $this->assertSame(['confirm', 'cancel'], $machine->getAvailableTransitions());
    }

    #[Test]
    public function it_applies_a_valid_transition(): void
    {
        $order = new OrderDto;
        $machine = $this->machine($order);

        $machine->apply('confirm');

        $this->assertSame('confirmed', $order->status);
        $this->assertSame('confirmed', $machine->getState());
        $this->assertTrue($machine->can('ship'));
        $this->assertFalse($machine->can('confirm'));
    }

    #[Test]
    public function it_rejects_undefined_transition(): void
    {
        $machine = $this->machine();

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage('not defined');

        $machine->apply('explode');
    }

    #[Test]
    public function it_rejects_transition_not_available_from_current_state(): void
    {
        $machine = $this->machine();

        $this->expectException(InvalidTransitionException::class);
        $this->expectExceptionMessage('not available');

        $machine->apply('ship');
    }

    #[Test]
    public function it_uses_initial_state_when_property_is_uninitialized(): void
    {
        $subject = new UninitializedStatusSubject;

        $machine = new StateMachine(
            subject: $subject,
            config: StateMachineConfig::fromArray('order', $this->orderGraphConfig()),
            accessor: new PropertyAccessor,
        );

        $this->assertSame('pending', $machine->getState());
    }

    #[Test]
    public function it_returns_subject_and_transition_context(): void
    {
        $order = new OrderDto;
        $machine = $this->machine($order);

        $this->assertSame($order, $machine->getSubject());

        $context = $machine->getTransitionContext('confirm', ['actor' => 'system']);

        $this->assertNotNull($context);
        $this->assertSame('confirm', $context->transition);
        $this->assertSame('pending', $context->from);
        $this->assertSame('confirmed', $context->to);
        $this->assertSame(['actor' => 'system'], $context->metadata);

        $this->assertNull($machine->getTransitionContext('ship'));
        $this->assertNull($machine->getTransitionContext('missing'));
    }

    #[Test]
    public function factory_creates_working_machine(): void
    {
        $order = new OrderDto;
        $factory = new StateMachineFactory;

        $machine = $factory->create($order, 'order', $this->orderGraphConfig());

        $machine->apply('confirm');

        $this->assertSame('confirmed', $order->status);
    }
}
