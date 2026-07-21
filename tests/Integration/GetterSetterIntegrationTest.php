<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Integration;

use JOOservices\StateMachine\Accessors\GetterSetterAccessor;
use JOOservices\StateMachine\StateMachineFactory;
use JOOservices\StateMachine\Tests\Fixtures\EncapsulatedOrder;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GetterSetterIntegrationTest extends TestCase
{
    #[Test]
    public function factory_can_use_getter_setter_accessor(): void
    {
        $order = new EncapsulatedOrder('pending');
        $factory = new StateMachineFactory(defaultAccessor: new GetterSetterAccessor);

        $machine = $factory->create($order, 'order', $this->orderGraphConfig());

        $machine->apply('confirm');

        $this->assertSame('confirmed', $order->getStatus());
        $this->assertSame('confirmed', $machine->getState());
    }
}
