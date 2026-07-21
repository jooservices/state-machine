<?php

declare(strict_types=1);

namespace JOOservices\StateMachine;

use JOOservices\StateMachine\Config\StateMachineConfig;
use JOOservices\StateMachine\Config\TransitionConfig;
use JOOservices\StateMachine\Contracts\CallbackInterface;
use JOOservices\StateMachine\Contracts\GuardInterface;
use JOOservices\StateMachine\Contracts\StateAccessorInterface;
use JOOservices\StateMachine\Contracts\StateMachineInterface;
use JOOservices\StateMachine\Events\TransitionCompleted;
use JOOservices\StateMachine\Events\TransitionFailed;
use JOOservices\StateMachine\Events\TransitionStarting;
use JOOservices\StateMachine\Exceptions\InvalidTransitionException;
use JOOservices\StateMachine\Exceptions\TransitionGuardException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

/**
 * Core finite state machine engine.
 *
 * Applies configuration-driven transitions to a subject object using
 * a pluggable state accessor. Supports guards, callbacks, and PSR-14 events.
 */
class StateMachine implements StateMachineInterface
{
    public function __construct(
        private readonly object $subject,
        private readonly StateMachineConfig $config,
        private readonly StateAccessorInterface $accessor,
        private readonly ?EventDispatcherInterface $eventDispatcher = null,
    ) {}

    public function can(string $transition): bool
    {
        $transitionConfig = $this->config->getTransition($transition);

        if ($transitionConfig === null) {
            return false;
        }

        $currentState = $this->getState();

        if (! in_array($currentState, $transitionConfig->from, true)) {
            return false;
        }

        $context = new TransitionContext(
            subject: $this->subject,
            transition: $transition,
            from: $currentState,
            to: $transitionConfig->to,
        );

        return $this->checkGuards($transitionConfig, $context);
    }

    public function apply(string $transition, array $metadata = []): void
    {
        $transitionConfig = $this->config->getTransition($transition);

        if ($transitionConfig === null) {
            throw InvalidTransitionException::notDefined($transition, $this->config->graphName);
        }

        $currentState = $this->getState();

        if (! in_array($currentState, $transitionConfig->from, true)) {
            throw InvalidTransitionException::notAvailable($transition, $currentState);
        }

        $context = new TransitionContext(
            subject: $this->subject,
            transition: $transition,
            from: $currentState,
            to: $transitionConfig->to,
            metadata: $metadata,
        );

        $this->executeGuardsOrFail($transitionConfig, $context);

        $this->executeCallbacks($transitionConfig->beforeCallbacks, $context);

        $this->dispatchEvent(new TransitionStarting($context));

        try {
            $this->accessor->setState($this->subject, $this->config->property, $transitionConfig->to);
        } catch (Throwable $exception) {
            $this->dispatchEvent(new TransitionFailed($context, $exception));

            throw $exception;
        }

        $this->executeCallbacks($transitionConfig->afterCallbacks, $context);

        $this->dispatchEvent(new TransitionCompleted($context));
    }

    public function getState(): string
    {
        $state = $this->accessor->getState($this->subject, $this->config->property);

        return $state ?? $this->config->initialState;
    }

    public function getAvailableTransitions(): array
    {
        $currentState = $this->getState();
        $available = [];

        foreach ($this->config->getTransitionsFromState($currentState) as $name => $transitionConfig) {
            $context = new TransitionContext(
                subject: $this->subject,
                transition: $name,
                from: $currentState,
                to: $transitionConfig->to,
            );

            if ($this->checkGuards($transitionConfig, $context)) {
                $available[] = $name;
            }
        }

        return $available;
    }

    public function getSubject(): object
    {
        return $this->subject;
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function getTransitionContext(string $transition, array $metadata = []): ?TransitionContext
    {
        $transitionConfig = $this->config->getTransition($transition);

        if ($transitionConfig === null) {
            return null;
        }

        $currentState = $this->getState();

        if (! in_array($currentState, $transitionConfig->from, true)) {
            return null;
        }

        return new TransitionContext(
            subject: $this->subject,
            transition: $transition,
            from: $currentState,
            to: $transitionConfig->to,
            metadata: $metadata,
        );
    }

    /**
     * Check all guards for a transition.
     */
    private function checkGuards(TransitionConfig $transitionConfig, TransitionContext $context): bool
    {
        foreach ($transitionConfig->guards as $guardClass) {
            if (! class_exists($guardClass)) {
                return false;
            }

            /** @var GuardInterface $guard */
            $guard = new $guardClass;

            if (! $guard->check($context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute guards and throw if any rejects.
     *
     * @throws TransitionGuardException
     */
    private function executeGuardsOrFail(TransitionConfig $transitionConfig, TransitionContext $context): void
    {
        foreach ($transitionConfig->guards as $guardClass) {
            if (! class_exists($guardClass)) {
                throw TransitionGuardException::rejected($context->transition, $guardClass);
            }

            /** @var GuardInterface $guard */
            $guard = new $guardClass;

            if (! $guard->check($context)) {
                throw TransitionGuardException::rejected($context->transition, $guardClass);
            }
        }
    }

    /**
     * Execute a list of callback classes.
     *
     * @param  list<class-string<CallbackInterface>>  $callbackClasses
     */
    private function executeCallbacks(array $callbackClasses, TransitionContext $context): void
    {
        foreach ($callbackClasses as $callbackClass) {
            if (! class_exists($callbackClass)) {
                continue;
            }

            /** @var CallbackInterface $callback */
            $callback = new $callbackClass;
            $callback->execute($context);
        }
    }

    /**
     * Dispatch a PSR-14 event if a dispatcher is configured.
     */
    private function dispatchEvent(object $event): void
    {
        $this->eventDispatcher?->dispatch($event);
    }
}
