<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures;

/**
 * Subject with an intentionally uninitialized status property.
 *
 * Used to verify accessors and StateMachine fall back behavior.
 */
final class UninitializedStatusSubject
{
    public string $status;

    public function __construct()
    {
        // Intentionally leave $status uninitialized for reflection tests.
    }
}
