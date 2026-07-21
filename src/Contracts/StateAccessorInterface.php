<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Contracts;

use Throwable;

/**
 * Strategy for reading and writing state from a subject object.
 *
 * Implementations handle different access patterns: direct property access,
 * getter/setter methods, or framework-specific attribute access.
 */
interface StateAccessorInterface
{
    /**
     * Read the current state value from the subject.
     *
     * Returns null if the property has not been set.
     */
    public function getState(object $subject, string $property): ?string;

    /**
     * Write a new state value to the subject.
     *
     * Implementations may throw when the write cannot be completed.
     *
     * @throws Throwable
     */
    public function setState(object $subject, string $property, string $state): void;
}
