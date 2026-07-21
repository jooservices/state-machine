<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Exceptions;

/**
 * Thrown when a transition is not available from the current state.
 */
class InvalidTransitionException extends StateMachineException
{
    public static function notAvailable(string $transition, string $currentState): self
    {
        return new self(
            sprintf(
                'Transition "%s" is not available from state "%s".',
                $transition,
                $currentState,
            ),
        );
    }

    public static function notDefined(string $transition, string $graphName): self
    {
        return new self(
            sprintf(
                'Transition "%s" is not defined in graph "%s".',
                $transition,
                $graphName,
            ),
        );
    }
}
