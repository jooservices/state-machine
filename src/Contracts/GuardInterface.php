<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Contracts;

use JOOservices\StateMachine\TransitionContext;

/**
 * Guard contract for transition validation.
 *
 * Guards are checked before a transition is applied. If any guard
 * returns false, the transition is rejected.
 */
interface GuardInterface
{
    /**
     * Check whether the transition should be allowed.
     *
     * @return bool True if the transition is allowed, false to reject it
     */
    public function check(TransitionContext $context): bool;
}
