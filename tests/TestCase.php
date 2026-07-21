<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @return array<string, mixed>
     */
    protected function orderGraphConfig(): array
    {
        return [
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
}
