<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Unit\Accessors;

use JOOservices\StateMachine\Accessors\PropertyAccessor;
use JOOservices\StateMachine\Tests\Fixtures\OrderDto;
use JOOservices\StateMachine\Tests\Fixtures\UninitializedStatusSubject;
use JOOservices\StateMachine\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class PropertyAccessorTest extends TestCase
{
    #[Test]
    public function it_reads_and_writes_public_properties(): void
    {
        $subject = new OrderDto(status: 'pending');
        $accessor = new PropertyAccessor;

        $this->assertSame('pending', $accessor->getState($subject, 'status'));

        $accessor->setState($subject, 'status', 'confirmed');

        $this->assertSame('confirmed', $subject->status);
        $this->assertSame('confirmed', $accessor->getState($subject, 'status'));
    }

    #[Test]
    public function it_returns_null_for_missing_property(): void
    {
        $subject = new OrderDto;
        $accessor = new PropertyAccessor;

        $this->assertNull($accessor->getState($subject, 'missing'));
    }

    #[Test]
    public function it_ignores_set_for_missing_property(): void
    {
        $subject = new OrderDto(status: 'pending');
        $accessor = new PropertyAccessor;

        $accessor->setState($subject, 'missing', 'confirmed');

        $this->assertSame('pending', $subject->status);
    }

    #[Test]
    public function it_returns_null_for_uninitialized_property(): void
    {
        $subject = new UninitializedStatusSubject;
        $accessor = new PropertyAccessor;

        $this->assertNull($accessor->getState($subject, 'status'));
    }

    #[Test]
    public function it_rejects_writes_to_initialized_readonly_properties(): void
    {
        $subject = new readonly class('pending')
        {
            public function __construct(public string $status) {}
        };
        $accessor = new PropertyAccessor;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('readonly property');

        $accessor->setState($subject, 'status', 'confirmed');
    }
}
