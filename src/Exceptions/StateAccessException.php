<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Exceptions;

/**
 * Thrown when a state accessor cannot read or write the configured property.
 */
class StateAccessException extends StateMachineException
{
    public static function missingProperty(object $subject, string $property): self
    {
        return new self(
            sprintf(
                'Cannot write state: property "%s" does not exist on %s.',
                $property,
                $subject::class,
            ),
        );
    }

    public static function missingSetter(object $subject, string $property): self
    {
        return new self(
            sprintf(
                'Cannot write state: no setter "set%s" on %s for property "%s".',
                ucfirst($property),
                $subject::class,
                $property,
            ),
        );
    }

    public static function readonlyProperty(object $subject, string $property): self
    {
        return new self(
            sprintf(
                'Cannot write state to readonly property "%s" on %s after initialization. Use a mutable property or GetterSetterAccessor with a setter.',
                $property,
                $subject::class,
            ),
        );
    }
}
