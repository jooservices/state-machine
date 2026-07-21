<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Integration;

use JOOservices\StateMachine\Accessors\PropertyAccessor;
use JOOservices\StateMachine\Config\StateMachineConfig;
use JOOservices\StateMachine\Contracts\StateAccessorInterface;
use JOOservices\StateMachine\Events\TransitionCompleted;
use JOOservices\StateMachine\Events\TransitionFailed;
use JOOservices\StateMachine\Events\TransitionStarting;
use JOOservices\StateMachine\StateMachine;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\Fixtures\RecordingEventDispatcher;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class EventDispatchTest extends TestCase
{
    #[Test]
    public function it_dispatches_starting_and_completed_events(): void
    {
        $dispatcher = new RecordingEventDispatcher;
        $order = new OrderDto;
        $machine = new StateMachine(
            subject: $order,
            config: StateMachineConfig::fromArray('order', $this->orderGraphConfig()),
            accessor: new PropertyAccessor,
            eventDispatcher: $dispatcher,
        );

        $machine->apply('confirm');

        $this->assertCount(2, $dispatcher->events);
        $this->assertInstanceOf(TransitionStarting::class, $dispatcher->events[0]);
        $this->assertInstanceOf(TransitionCompleted::class, $dispatcher->events[1]);
        $this->assertSame('confirm', $dispatcher->events[0]->context->transition);
    }

    #[Test]
    public function it_dispatches_failed_event_when_set_state_throws(): void
    {
        $dispatcher = new RecordingEventDispatcher;

        $accessor = new class implements StateAccessorInterface
        {
            public function getState(object $subject, string $property): ?string
            {
                return is_object($subject) && property_exists($subject, 'status') && is_string($subject->status)
                    ? $subject->status
                    : null;
            }

            public function setState(object $subject, string $property, string $state): void
            {
                throw new RuntimeException('write failed');
            }
        };

        $order = new OrderDto;
        $machine = new StateMachine(
            subject: $order,
            config: StateMachineConfig::fromArray('order', $this->orderGraphConfig()),
            accessor: $accessor,
            eventDispatcher: $dispatcher,
        );

        try {
            $machine->apply('confirm');
            $this->fail('Expected RuntimeException');
        } catch (RuntimeException $exception) {
            $this->assertSame('write failed', $exception->getMessage());
        }

        $this->assertCount(2, $dispatcher->events);
        $this->assertInstanceOf(TransitionStarting::class, $dispatcher->events[0]);
        $this->assertInstanceOf(TransitionFailed::class, $dispatcher->events[1]);
        $this->assertSame('write failed', $dispatcher->events[1]->exception->getMessage());
    }
}
