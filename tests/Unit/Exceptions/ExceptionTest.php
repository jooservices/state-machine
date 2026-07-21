<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit\Exceptions;

use JOOservices\StateMachine\Exceptions\InvalidConfigurationException;
use JOOservices\StateMachine\Exceptions\InvalidTransitionException;
use JOOservices\StateMachine\Exceptions\StateMachineException;
use JOOservices\StateMachine\Exceptions\TransitionGuardException;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class ExceptionTest extends TestCase
{
    #[Test]
    public function domain_exceptions_extend_base(): void
    {
        $this->assertInstanceOf(
            StateMachineException::class,
            InvalidConfigurationException::missingKey('order', 'property'),
        );
        $this->assertInstanceOf(
            StateMachineException::class,
            InvalidTransitionException::notAvailable('confirm', 'shipped'),
        );
        $this->assertInstanceOf(
            StateMachineException::class,
            TransitionGuardException::rejected('confirm', 'App\\Guards\\PaidGuard'),
        );
    }

    #[Test]
    public function messages_include_context(): void
    {
        $this->assertStringContainsString(
            'property',
            InvalidConfigurationException::missingKey('order', 'property')->getMessage(),
        );
        $this->assertStringContainsString(
            'confirm',
            InvalidTransitionException::notDefined('confirm', 'order')->getMessage(),
        );
        $this->assertStringContainsString(
            'shipped',
            InvalidTransitionException::notAvailable('confirm', 'shipped')->getMessage(),
        );
        $this->assertStringContainsString(
            'PaidGuard',
            TransitionGuardException::rejected('confirm', 'App\\Guards\\PaidGuard')->getMessage(),
        );
    }
}
