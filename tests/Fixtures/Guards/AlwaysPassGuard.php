<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures\Guards;

use JOOservices\StateMachine\Contracts\GuardInterface;
use JOOservices\StateMachine\TransitionContext;

class AlwaysPassGuard implements GuardInterface
{
    public function check(TransitionContext $context): bool
    {
        return true;
    }
}
