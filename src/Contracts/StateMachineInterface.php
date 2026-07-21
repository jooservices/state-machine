<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Contracts;

use JOOservices\StateMachine\TransitionContext;

/**
 * Core state machine operations contract.
 *
 * Defines the public API for interacting with a finite state machine
 * bound to a specific subject object and configuration graph.
 */
interface StateMachineInterface
{
    /**
     * Check whether a transition can be applied from the current state.
     *
     * Returns false if the transition is not defined for the current state
     * or if any guard rejects it.
     */
    public function can(string $transition): bool;

    /**
     * Apply a transition, changing the subject's state.
     *
     * @param  array<string, mixed>  $metadata  Optional context data passed to guards, callbacks, and events
     *
     * @throws \JOOservices\StateMachine\Exceptions\InvalidTransitionException
     * @throws \JOOservices\StateMachine\Exceptions\TransitionGuardException
     */
    public function apply(string $transition, array $metadata = []): void;

    /**
     * Get the current state of the subject.
     */
    public function getState(): string;

    /**
     * Get the names of all transitions available from the current state.
     *
     * @return list<string>
     */
    public function getAvailableTransitions(): array;

    /**
     * Get the subject object this state machine operates on.
     */
    public function getSubject(): object;

    /**
     * Get the transition context for a named transition without applying it.
     *
     * Returns null if the transition is not defined for the current state.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function getTransitionContext(string $transition, array $metadata = []): ?TransitionContext;
}
