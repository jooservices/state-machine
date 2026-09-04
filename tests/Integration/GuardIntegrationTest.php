<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Integration;

use JOOservices\StateMachine\Exceptions\TransitionGuardException;
use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\Guards\AlwaysFailGuard;
use JOOservices\StateMachine\Tests\Fixtures\Guards\AlwaysPassGuard;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GuardIntegrationTest extends TestCase
{
    #[Test]
    public function passing_guard_allows_transition(): void
    {
        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['guards'] = [AlwaysPassGuard::class];

        $order = new OrderDto;
        $machine = (new StateMachineFactory)->create($order, 'order', $config);

        $this->assertTrue($machine->can('confirm'));
        $machine->apply('confirm');
        $this->assertSame('confirmed', $order->status);
    }

    #[Test]
    public function failing_guard_blocks_can_and_apply(): void
    {
        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['guards'] = [AlwaysFailGuard::class];

        $order = new OrderDto;
        $machine = (new StateMachineFactory)->create($order, 'order', $config);

        $this->assertFalse($machine->can('confirm'));
        $this->assertNotContains('confirm', $machine->getAvailableTransitions());

        $this->expectException(TransitionGuardException::class);
        $this->expectExceptionMessage(AlwaysFailGuard::class);

        $machine->apply('confirm');
    }

    #[Test]
    public function missing_guard_class_fails_at_config_time(): void
    {
        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['guards'] = ['App\\DoesNotExist\\Guard'];

        $this->expectException(\JOOservices\StateMachine\Exceptions\InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid class reference');

        (new StateMachineFactory)->create(new OrderDto, 'order', $config);
    }
}
