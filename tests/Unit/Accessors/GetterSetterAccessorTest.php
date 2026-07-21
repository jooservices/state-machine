<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit\Accessors;

use JOOservices\StateMachine\Accessors\GetterSetterAccessor;
use JOOservices\StateMachine\Tests\Fixtures\EncapsulatedOrder;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class GetterSetterAccessorTest extends TestCase
{
    #[Test]
    public function it_reads_and_writes_via_getters_and_setters(): void
    {
        $subject = new EncapsulatedOrder('pending');
        $accessor = new GetterSetterAccessor;

        $this->assertSame('pending', $accessor->getState($subject, 'status'));

        $accessor->setState($subject, 'status', 'confirmed');

        $this->assertSame('confirmed', $subject->getStatus());
    }

    #[Test]
    public function it_returns_null_when_no_getter_exists(): void
    {
        $subject = new class
        {
            public function setStatus(string $status): void {}
        };
        $accessor = new GetterSetterAccessor;

        $this->assertNull($accessor->getState($subject, 'status'));
    }

    #[Test]
    public function it_ignores_set_when_no_setter_exists(): void
    {
        $subject = new EncapsulatedOrder('pending');
        $accessor = new GetterSetterAccessor;

        // property name with no matching setter
        $accessor->setState($subject, 'missing', 'confirmed');

        $this->assertSame('pending', $subject->getStatus());
    }

    #[Test]
    public function it_supports_property_named_accessor_method(): void
    {
        $subject = new class
        {
            private string $state = 'open';

            public function state(): string
            {
                return $this->state;
            }

            public function setState(string $state): void
            {
                $this->state = $state;
            }
        };
        $accessor = new GetterSetterAccessor;

        $this->assertSame('open', $accessor->getState($subject, 'state'));
        $accessor->setState($subject, 'state', 'closed');
        $this->assertSame('closed', $subject->state());
    }
}
