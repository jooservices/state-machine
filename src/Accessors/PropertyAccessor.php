<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Accessors;

use JOOservices\StateMachine\Contracts\StateAccessorInterface;
use JOOservices\StateMachine\Exceptions\StateAccessException;
use ReflectionObject;

/**
 * Reads and writes state via public or promoted properties using reflection.
 */
final class PropertyAccessor implements StateAccessorInterface
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
            throw StateAccessException::missingProperty($subject, $property);
        }

        $prop = $reflection->getProperty($property);

        if ($prop->isReadOnly() && $prop->isInitialized($subject)) {
            throw StateAccessException::readonlyProperty($subject, $property);
        }

        $prop->setValue($subject, $state);
    }
}
