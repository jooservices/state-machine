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
        $guards = self::optionalClassList($name, $config, 'guards', GuardInterface::class);
        /** @var list<class-string<CallbackInterface>> $beforeCallbacks */
        $beforeCallbacks = self::optionalNestedClassList($name, $config, 'callbacks', 'before', CallbackInterface::class);
        /** @var list<class-string<CallbackInterface>> $afterCallbacks */
        $afterCallbacks = self::optionalNestedClassList($name, $config, 'callbacks', 'after', CallbackInterface::class);

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
        if (! isset($config[$key]) || ! is_string($config[$key]) || $config[$key] === '') {
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
        if (! isset($config[$key]) || ! is_array($config[$key]) || $config[$key] === []) {
            throw InvalidConfigurationException::missingKey($name, $key);
        }

        $values = [];

        foreach ($config[$key] as $value) {
            if (! is_string($value) || $value === '') {
                throw InvalidConfigurationException::missingKey($name, $key);
            }

            $values[] = $value;
        }

        return $values;
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  class-string  $interface
     * @return list<class-string>
     *
     * @throws InvalidConfigurationException
     */
    private static function optionalClassList(string $name, array $config, string $key, string $interface): array
    {
        if (! isset($config[$key]) || ! is_array($config[$key])) {
            return [];
        }

        $values = [];

        foreach ($config[$key] as $class) {
            if (! is_string($class) || $class === '') {
                $invalid = is_scalar($class) ? (string) $class : get_debug_type($class);
                throw InvalidConfigurationException::invalidClassReference($name, $key, $invalid, $interface);
            }

            if (! class_exists($class) || ! is_a($class, $interface, true)) {
                throw InvalidConfigurationException::invalidClassReference($name, $key, $class, $interface);
            }

            $values[] = $class;
        }

        return $values;
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  class-string  $interface
     * @return list<class-string>
     *
     * @throws InvalidConfigurationException
     */
    private static function optionalNestedClassList(
        string $name,
        array $config,
        string $parentKey,
        string $childKey,
        string $interface,
    ): array {
        if (! isset($config[$parentKey]) || ! is_array($config[$parentKey])) {
            return [];
        }

        $parent = $config[$parentKey];

        if (! isset($parent[$childKey]) || ! is_array($parent[$childKey])) {
            return [];
        }

        $path = $parentKey.'.'.$childKey;
        $values = [];

        foreach ($parent[$childKey] as $class) {
            if (! is_string($class) || $class === '') {
                $invalid = is_scalar($class) ? (string) $class : get_debug_type($class);
                throw InvalidConfigurationException::invalidClassReference($name, $path, $invalid, $interface);
            }

            if (! class_exists($class) || ! is_a($class, $interface, true)) {
                throw InvalidConfigurationException::invalidClassReference($name, $path, $class, $interface);
            }

            $values[] = $class;
        }

        return $values;
    }
}
