<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Exceptions;

/**
 * Thrown when a state machine configuration is malformed.
 */
class InvalidConfigurationException extends StateMachineException
{
    public static function missingKey(string $graphName, string $key): self
    {
        return new self(
            sprintf(
                'Missing or invalid key "%s" in state machine configuration for graph "%s".',
                $key,
                $graphName,
            ),
        );
    }

    /**
     * @param  list<string>  $validStates
     */
    public static function invalidInitialState(string $graphName, string $initialState, array $validStates): self
    {
        return new self(
            sprintf(
                'Initial state "%s" in graph "%s" is not in the states list [%s].',
                $initialState,
                $graphName,
                implode(', ', $validStates),
            ),
        );
    }

    /**
     * @param  list<string>  $validStates
     */
    public static function invalidTransitionState(
        string $graphName,
        string $transitionName,
        string $direction,
        string $state,
        array $validStates,
    ): self {
        return new self(
            sprintf(
                'Transition "%s" in graph "%s" references %s-state "%s" which is not in the states list [%s].',
                $transitionName,
                $graphName,
                $direction,
                $state,
                implode(', ', $validStates),
            ),
        );
    }
}
