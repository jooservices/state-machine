<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Benchmark;

use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use PhpBench\Attributes as Bench;

final class StateMachineBench
{
    private StateMachineFactory $factory;

    /** @var array<string, mixed> */
    private array $config;

    public function __construct()
    {
        $this->factory = new StateMachineFactory;
        $this->config = [
            'property' => 'status',
            'states' => ['pending', 'confirmed', 'shipped', 'cancelled'],
            'initial_state' => 'pending',
            'transitions' => [
                'confirm' => [
                    'from' => ['pending'],
                    'to' => 'confirmed',
                ],
                'ship' => [
                    'from' => ['confirmed'],
                    'to' => 'shipped',
                ],
                'cancel' => [
                    'from' => ['pending', 'confirmed'],
                    'to' => 'cancelled',
                ],
            ],
        ];
    }

    #[Bench\Revs(1000)]
    #[Bench\Iterations(5)]
    public function benchCreateAndApply(): void
    {
        $order = new OrderDto;
        $machine = $this->factory->create($order, 'order', $this->config);
        $machine->apply('confirm');
    }

    #[Bench\Revs(1000)]
    #[Bench\Iterations(5)]
    public function benchCanCheck(): void
    {
        $order = new OrderDto;
        $machine = $this->factory->create($order, 'order', $this->config);
        $machine->can('confirm');
        $machine->can('ship');
    }
}
