<?php

declare(strict_types=1);

namespace JOOservices\StateMachine;

/**
 * Immutable context object passed to guards, callbacks, and events.
 *
 * Carries all information about a transition attempt including the subject,
 * transition name, source and target states, and optional metadata.
 */
readonly class TransitionContext
{
    /**
     * @param  array<string, mixed>  $metadata  Optional context data
     */
    public function __construct(
        public object $subject,
        public string $transition,
        public string $from,
        public string $to,
        public array $metadata = [],
    ) {}
}
