<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\OuterEvent;

use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

class OuterEventTest extends TestCase
{
    /**
     * @test
     */
    public function objectInitialization(): void
    {
        $eventTime = time();
        $event = new OuterEvent('foo_bar_event', ['foo' => 'bar'], $eventTime);

        $this->assertSame('foo_bar_event', $event->eventType());
        $this->assertSame(['foo' => 'bar'], $event->payload());
        $this->assertSame($eventTime, $event->eventTime());
    }

    /**
     * @test
     */
    public function convertsToArray(): void
    {
        $eventTime = time();
        $event = new OuterEvent('foo_event', ['foo' => 'payload'], $eventTime);

        $this->assertSame(
            [
                'event_type' => 'foo_event',
                'payload' => ['foo' => 'payload'],
                'event_time' => $eventTime
            ],
            $event->toArray()
        );
    }

    /**
     * @test
     */
    public function beingSerializedIntoJson(): void
    {
        $eventTime = time();
        $event = new OuterEvent('foo_bar_event', ['foo' => 'bar'], $eventTime);

        $actualJson = json_encode($event);

        $this->assertJson($actualJson);
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'event_type' => 'foo_bar_event',
                    'payload' => ['foo' => 'bar'],
                    'event_time' => $eventTime
                ]
            ),
            $actualJson
        );
    }

    /**
     * @test
     */
    public function beingCreatedFromArray(): void
    {
        $eventTime = time();
        $event = OuterEvent::fromArray(
            [
                'event_type' => 'foo',
                'payload' => ['foo' => 'payload'],
                'event_time' => $eventTime
            ]
        );

        $this->assertSame('foo', $event->eventType());
        $this->assertSame(['foo' => 'payload'], $event->payload());
        $this->assertSame($eventTime, $event->eventTime());
    }
}
