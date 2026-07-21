<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Contracts;

use JOOservices\StateMachine\TransitionContext;

/**
 * Callback contract for transition lifecycle hooks.
 *
 * Callbacks execute before or after a transition is applied, allowing
 * side effects such as logging, notifications, or additional state changes.
 */
interface CallbackInterface
{
    /**
     * Execute the callback logic.
     */
    public function execute(TransitionContext $context): void;
}
