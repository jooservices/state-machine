<?php

declare(strict_types=1);

namespace JOOservices\StateMachine\Tests\Fixtures;

use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Test double that records dispatched events.
 */
final class RecordingEventDispatcher implements EventDispatcherInterface
{
    /** @var list<object> */
    public array $events = [];

    public function dispatch(object $event): object
    {
        $this->events[] = $event;

        return $event;
    }
}
