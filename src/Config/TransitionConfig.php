<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Config;

use JOOservices\StateMachine\Contracts\CallbackInterface;
use JOOservices\StateMachine\Contracts\GuardInterface;
use JOOservices\StateMachine\Exceptions\InvalidConfigurationException;

/**
 * Immutable configuration for a single transition.
 *
 * Holds the source states, target state, guard class names, and
 * before/after callback class names for one named transition.
 */
readonly class TransitionConfig
{
    /**
     * @param  list<string>  $from  Source states this transition can originate from
     * @param  string  $to  Target state after the transition
     * @param  list<class-string<GuardInterface>>  $guards
     * @param  list<class-string<CallbackInterface>>  $beforeCallbacks
     * @param  list<class-string<CallbackInterface>>  $afterCallbacks
     */
    public function __construct(
        public array $from,
        public string $to,
        public array $guards = [],
        public array $beforeCallbacks = [],
        public array $afterCallbacks = [],
    ) {}

    /**
     * Create a TransitionConfig from a raw configuration array.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException
     */
    public static function fromArray(string $name, array $config): self
    {
        $from = self::requireStringList($name, $config, 'from');
        $to = self::requireString($name, $config, 'to');

        /** @var list<class-string<GuardInterface>> $guards */
        $guards = self::optionalClassList($config, 'guards');
        /** @var list<class-string<CallbackInterface>> $beforeCallbacks */
        $beforeCallbacks = self::optionalNestedClassList($config, 'callbacks', 'before');
        /** @var list<class-string<CallbackInterface>> $afterCallbacks */
        $afterCallbacks = self::optionalNestedClassList($config, 'callbacks', 'after');

        return new self(
            from: $from,
            to: $to,
            guards: $guards,
            beforeCallbacks: $beforeCallbacks,
            afterCallbacks: $afterCallbacks,
        );
    }

    /**
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidConfigurationException
     */
    private static function requireString(string $name, array $config, string $key): string
    {
        if (! isset($config[$key]) || ! is_string($config[$key])) {
            throw InvalidConfigurationException::missingKey($name, $key);
        }

        return $config[$key];
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<string>
     *
     * @throws InvalidConfigurationException
     */
    private static function requireStringList(string $name, array $config, string $key): array
    {
        if (! isset($config[$key]) || ! is_array($config[$key])) {
            throw InvalidConfigurationException::missingKey($name, $key);
        }

        /** @var list<string> $values */
        $values = array_values($config[$key]);

        return $values;
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<class-string>
     */
    private static function optionalClassList(array $config, string $key): array
    {
        if (! isset($config[$key]) || ! is_array($config[$key])) {
            return [];
        }

        /** @var list<class-string> $values */
        $values = array_values($config[$key]);

        return $values;
    }

    /**
     * @param  array<string, mixed>  $config
     * @return list<class-string>
     */
    private static function optionalNestedClassList(array $config, string $parentKey, string $childKey): array
    {
        if (! isset($config[$parentKey]) || ! is_array($config[$parentKey])) {
            return [];
        }

        $parent = $config[$parentKey];

        if (! isset($parent[$childKey]) || ! is_array($parent[$childKey])) {
            return [];
        }

        /** @var list<class-string> $values */
        $values = array_values($parent[$childKey]);

        return $values;
    }
}
