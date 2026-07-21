<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Events;

use JOOservices\StateMachine\TransitionContext;

/**
 * Dispatched after a transition has been successfully applied.
 *
 * The subject's state has been updated at this point.
 */
readonly class TransitionCompleted
{
    public function __construct(
        public TransitionContext $context,
    ) {}
}
