<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Accessors;

use JOOservices\StateMachine\Contracts\StateAccessorInterface;
use ReflectionObject;
use RuntimeException;

/**
 * Reads and writes state via public or promoted properties using reflection.
 */
class PropertyAccessor implements StateAccessorInterface
{
    public function getState(object $subject, string $property): ?string
    {
        $reflection = new ReflectionObject($subject);

        if (! $reflection->hasProperty($property)) {
            return null;
        }

        $prop = $reflection->getProperty($property);

        if (! $prop->isInitialized($subject)) {
            return null;
        }

        $value = $prop->getValue($subject);

        return is_string($value) ? $value : null;
    }

    public function setState(object $subject, string $property, string $state): void
    {
        $reflection = new ReflectionObject($subject);

        if (! $reflection->hasProperty($property)) {
            return;
        }

        $prop = $reflection->getProperty($property);

        if ($prop->isReadOnly() && $prop->isInitialized($subject)) {
            throw new RuntimeException(
                sprintf(
                    'Cannot write state to readonly property "%s" on %s after initialization. Use a mutable property or GetterSetterAccessor with a setter.',
                    $property,
                    $subject::class,
                ),
            );
        }

        $prop->setValue($subject, $state);
    }
}
