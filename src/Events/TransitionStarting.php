<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Events;

use JOOservices\StateMachine\TransitionContext;

/**
 * Dispatched before a transition is applied.
 *
 * At this point guards have passed and the state is about to change.
 */
readonly class TransitionStarting
{
    public function __construct(
        public TransitionContext $context,
    ) {}
}
