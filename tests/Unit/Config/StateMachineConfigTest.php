<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit\Config;

use JOOservices\StateMachine\Config\StateMachineConfig;
use JOOservices\StateMachine\Exceptions\InvalidConfigurationException;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class StateMachineConfigTest extends TestCase
{
    #[Test]
    public function it_builds_from_a_valid_array(): void
    {
        $config = StateMachineConfig::fromArray('order', $this->orderGraphConfig());

        $this->assertSame('order', $config->graphName);
        $this->assertSame('status', $config->property);
        $this->assertSame('pending', $config->initialState);
        $this->assertTrue($config->hasState('confirmed'));
        $this->assertTrue($config->hasTransition('confirm'));
        $this->assertNotNull($config->getTransition('confirm'));
        $this->assertNull($config->getTransition('missing'));
    }

    #[Test]
    public function it_lists_transitions_from_a_state(): void
    {
        $config = StateMachineConfig::fromArray('order', $this->orderGraphConfig());

        $fromPending = $config->getTransitionsFromState('pending');

        $this->assertArrayHasKey('confirm', $fromPending);
        $this->assertArrayHasKey('cancel', $fromPending);
        $this->assertArrayNotHasKey('ship', $fromPending);
    }

    #[Test]
    public function it_requires_property(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "property"');

        $config = $this->orderGraphConfig();
        unset($config['property']);

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_requires_states(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "states"');

        $config = $this->orderGraphConfig();
        $config['states'] = [];

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_requires_transitions_key(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "transitions"');

        $config = $this->orderGraphConfig();
        unset($config['transitions']);

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_requires_initial_state(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "initial_state"');

        $config = $this->orderGraphConfig();
        unset($config['initial_state']);

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_rejects_initial_state_not_in_states(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Initial state "ghost"');

        $config = $this->orderGraphConfig();
        $config['initial_state'] = 'ghost';

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_rejects_transition_from_state_not_in_states(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('from-state "ghost"');

        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['from'] = ['ghost'];

        StateMachineConfig::fromArray('order', $config);
    }

    #[Test]
    public function it_rejects_transition_to_state_not_in_states(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('to-state "ghost"');

        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['to'] = 'ghost';

        StateMachineConfig::fromArray('order', $config);
    }
}
