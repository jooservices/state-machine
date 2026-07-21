<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit\Config;

use JOOservices\StateMachine\Config\TransitionConfig;
use JOOservices\StateMachine\Exceptions\InvalidConfigurationException;
use JOOservices\StateMachine\Tests\Fixtures\Callbacks\LogCallback;
use JOOservices\StateMachine\Tests\Fixtures\Guards\AlwaysPassGuard;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class TransitionConfigTest extends TestCase
{
    #[Test]
    public function it_builds_minimal_transition(): void
    {
        $config = TransitionConfig::fromArray('confirm', [
            'from' => ['pending'],
            'to' => 'confirmed',
        ]);

        $this->assertSame(['pending'], $config->from);
        $this->assertSame('confirmed', $config->to);
        $this->assertSame([], $config->guards);
        $this->assertSame([], $config->beforeCallbacks);
        $this->assertSame([], $config->afterCallbacks);
    }

    #[Test]
    public function it_parses_guards_and_callbacks(): void
    {
        $config = TransitionConfig::fromArray('confirm', [
            'from' => ['pending'],
            'to' => 'confirmed',
            'guards' => [AlwaysPassGuard::class],
            'callbacks' => [
                'before' => [LogCallback::class],
                'after' => [LogCallback::class],
            ],
        ]);

        $this->assertSame([AlwaysPassGuard::class], $config->guards);
        $this->assertSame([LogCallback::class], $config->beforeCallbacks);
        $this->assertSame([LogCallback::class], $config->afterCallbacks);
    }

    #[Test]
    public function it_requires_from(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "from"');

        TransitionConfig::fromArray('confirm', [
            'to' => 'confirmed',
        ]);
    }

    #[Test]
    public function it_requires_to(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Missing or invalid key "to"');

        TransitionConfig::fromArray('confirm', [
            'from' => ['pending'],
        ]);
    }
}
