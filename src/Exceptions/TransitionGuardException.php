<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Exceptions;

/**
 * Thrown when a guard rejects a transition.
 */
class TransitionGuardException extends StateMachineException
{
    public static function rejected(string $transition, string $guardClass): self
    {
        return new self(
            sprintf(
                'Transition "%s" was rejected by guard "%s".',
                $transition,
                $guardClass,
            ),
        );
    }
}
