<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Events;

use JOOservices\StateMachine\TransitionContext;

/**
 * Dispatched after guards pass and before before-callbacks / state write.
 */
readonly class TransitionStarting
{
    public function __construct(
        public TransitionContext $context,
    ) {}
}
