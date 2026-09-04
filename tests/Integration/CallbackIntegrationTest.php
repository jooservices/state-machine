<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Integration;

use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\Callbacks\LogCallback;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CallbackIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        LogCallback::reset();
    }

    #[Test]
    public function before_and_after_callbacks_run_on_apply(): void
    {
        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['callbacks'] = [
            'before' => [LogCallback::class],
            'after' => [LogCallback::class],
        ];

        $order = new OrderDto;
        $machine = (new StateMachineFactory)->create($order, 'order', $config);

        $machine->apply('confirm');

        $this->assertCount(2, LogCallback::$log);
        $this->assertSame('confirm', LogCallback::$log[0]['transition']);
        $this->assertSame('pending', LogCallback::$log[0]['from']);
        $this->assertSame('confirmed', LogCallback::$log[1]['to']);
        $this->assertSame('confirmed', $order->status);
    }

    #[Test]
    public function missing_callback_class_fails_at_config_time(): void
    {
        $config = $this->orderGraphConfig();
        $config['transitions']['confirm']['callbacks'] = [
            'before' => ['App\\DoesNotExist\\Callback'],
            'after' => [LogCallback::class],
        ];

        $this->expectException(\JOOservices\StateMachine\Exceptions\InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid class reference');

        (new StateMachineFactory)->create(new OrderDto, 'order', $config);
    }
}
