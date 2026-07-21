<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures\Callbacks;

use JOOservices\StateMachine\Contracts\CallbackInterface;
use JOOservices\StateMachine\TransitionContext;

/**
 * Records executions for assertions in integration tests.
 */
class LogCallback implements CallbackInterface
{
    /** @var list<array{transition: string, from: string, to: string}> */
    public static array $log = [];

    public static function reset(): void
    {
        self::$log = [];
    }

    public function execute(TransitionContext $context): void
    {
        self::$log[] = [
            'transition' => $context->transition,
            'from' => $context->from,
            'to' => $context->to,
        ];
    }
}
