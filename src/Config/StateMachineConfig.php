<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Config;

use JOOservices\StateMachine\Exceptions\InvalidConfigurationException;

/**
 * Immutable validated configuration for a state machine graph.
 *
 * Constructed from a raw configuration array, this value object validates
 * all states, transitions, and the initial state at construction time.
 */
readonly class StateMachineConfig
{
    /**
     * @param  string  $graphName  Unique identifier for this state machine graph
     * @param  string  $property  The object property that holds the state value
     * @param  list<string>  $states  All valid states in this graph
     * @param  array<string, TransitionConfig>  $transitions  Named transitions
     * @param  string  $initialState  The default state for new subjects
     */
    public function __construct(
        public string $graphName,
        public string $property,
        public array $states,
        public array $transitions,
        public string $initialState,
    ) {}

    /**
     * Create a StateMachineConfig from a raw configuration array.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException
     */
    public static function fromArray(string $graphName, array $config): self
    {
        $property = self::requireString($graphName, $config, 'property');
        $states = self::requireNonEmptyStringList($graphName, $config, 'states');
        $initialState = self::requireString($graphName, $config, 'initial_state');
        $rawTransitions = self::requireArray($graphName, $config, 'transitions');

        if (! in_array($initialState, $states, true)) {
            throw InvalidConfigurationException::invalidInitialState($graphName, $initialState, $states);
        }

        return new self(
            graphName: $graphName,
            property: $property,
            states: $states,
            transitions: self::parseTransitions($graphName, $rawTransitions, $states),
            initialState: $initialState,
        );
    }

    /**
     * Check whether a state is valid in this graph.
     */
    public function hasState(string $state): bool
    {
        return in_array($state, $this->states, true);
    }

    /**
     * Check whether a named transition exists.
     */
    public function hasTransition(string $transition): bool
    {
        return isset($this->transitions[$transition]);
    }

    /**
     * Get the transition config for a named transition.
     */
    public function getTransition(string $transition): ?TransitionConfig
    {
        return $this->transitions[$transition] ?? null;
    }

    /**
     * Get all transitions available from a given state.
     *
     * @return array<string, TransitionConfig>
     */
    public function getTransitionsFromState(string $state): array
    {
        $available = [];

        foreach ($this->transitions as $name => $transition) {
            if (in_array($state, $transition->from, true)) {
                $available[$name] = $transition;
            }
        }

        return $available;
    }

    /**
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException
     */
    private static function requireString(string $graphName, array $config, string $key): string
    {
        if (! isset($config[$key]) || ! is_string($config[$key])) {
            throw InvalidConfigurationException::missingKey($graphName, $key);
        }

        return $config[$key];
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<string>
     *
     * @throws InvalidConfigurationException
     */
    private static function requireNonEmptyStringList(string $graphName, array $config, string $key): array
    {
        if (! isset($config[$key]) || ! is_array($config[$key]) || $config[$key] === []) {
            throw InvalidConfigurationException::missingKey($graphName, $key);
        }

        /** @var list<string> $values */
        $values = array_values($config[$key]);

        return $values;
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<array-key, mixed>
     *
     * @throws InvalidConfigurationException
     */
    private static function requireArray(string $graphName, array $config, string $key): array
    {
        if (! isset($config[$key]) || ! is_array($config[$key])) {
            throw InvalidConfigurationException::missingKey($graphName, $key);
        }

        /** @var array<array-key, mixed> $value */
        $value = $config[$key];

        return $value;
    }

    /**
     * @param  array<array-key, mixed>  $rawTransitions
     * @param  list<string>  $states
     * @return array<string, TransitionConfig>
     *
     * @throws InvalidConfigurationException
     */
    private static function parseTransitions(string $graphName, array $rawTransitions, array $states): array
    {
        $transitions = [];

        foreach ($rawTransitions as $transitionName => $transitionConfig) {
            if (! is_string($transitionName) || ! is_array($transitionConfig)) {
                throw InvalidConfigurationException::missingKey($graphName, 'transitions');
            }

            /** @var array<string, mixed> $typedConfig */
            $typedConfig = $transitionConfig;
            $transitionObj = TransitionConfig::fromArray($transitionName, $typedConfig);
            self::validateTransitionStates($graphName, $transitionName, $transitionObj, $states);
            $transitions[$transitionName] = $transitionObj;
        }

        return $transitions;
    }

    /**
     * Validate that a transition's from/to states exist in the states list.
     *
     * @param  list<string>  $validStates
     *
     * @throws InvalidConfigurationException
     */
    private static function validateTransitionStates(
        string $graphName,
        string $transitionName,
        TransitionConfig $transition,
        array $validStates,
    ): void {
        foreach ($transition->from as $fromState) {
            if (! in_array($fromState, $validStates, true)) {
                throw InvalidConfigurationException::invalidTransitionState(
                    $graphName,
                    $transitionName,
                    'from',
                    $fromState,
                    $validStates,
                );
            }
        }

        if (! in_array($transition->to, $validStates, true)) {
            throw InvalidConfigurationException::invalidTransitionState(
                $graphName,
                $transitionName,
                'to',
                $transition->to,
                $validStates,
            );
        }
    }
}
