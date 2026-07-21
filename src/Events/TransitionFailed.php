<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Events;

use JOOservices\StateMachine\TransitionContext;
use Throwable;

/**
 * Dispatched when a transition fails.
 *
 * Carries both the transition context and the exception that caused the failure.
 */
readonly class TransitionFailed
{
    public function __construct(
        public TransitionContext $context,
        public Throwable $exception,
    ) {}
}
