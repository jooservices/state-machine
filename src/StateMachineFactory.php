<?php

declare(strict_types=1);

namespace JOOservices\StateMachine;

use JOOservices\StateMachine\Accessors\PropertyAccessor;
use JOOservices\StateMachine\Config\StateMachineConfig;
use JOOservices\StateMachine\Contracts\StateAccessorInterface;
use JOOservices\StateMachine\Contracts\StateMachineInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Factory for creating StateMachine instances from configuration arrays.
 *
 * Provides a convenient API for creating state machines without
 * manually constructing config objects.
 */
class StateMachineFactory
{
    public function __construct(
        private readonly StateAccessorInterface $defaultAccessor = new PropertyAccessor,
        private readonly ?EventDispatcherInterface $eventDispatcher = null,
    ) {}

    /**
     * Create a state machine for a subject and graph configuration.
     *
     * @param  array<string, mixed>  $config  Raw configuration array
     */
    public function create(
        object $subject,
        string $graphName,
        array $config,
        ?StateAccessorInterface $accessor = null,
    ): StateMachineInterface {
        $machineConfig = StateMachineConfig::fromArray($graphName, $config);

        return new StateMachine(
            subject: $subject,
            config: $machineConfig,
            accessor: $accessor ?? $this->defaultAccessor,
            eventDispatcher: $this->eventDispatcher,
        );
    }
}
