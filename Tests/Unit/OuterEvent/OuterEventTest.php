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
        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar']);

        $this->assertSame('foo_bar_event', $outerEvent->getEventType());
        $this->assertSame(['foo' => 'bar'], $outerEvent->getPayload());
    }

    /**
     * @test
     */
    public function convertsToArray(): void
    {
        $outerEvent = new OuterEvent('foo_event', ['foo' => 'payload']);

        $this->assertSame(
            [
                'event_type' => 'foo_event',
                'payload' => ['foo' => 'payload']
            ],
            $outerEvent->toArray()
        );
    }

    /**
     * @test
     */
    public function beingSerializedIntoJson(): void
    {
        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar']);

        $actualJson = json_encode($outerEvent);

        $this->assertJson($actualJson);
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                [
                    'event_type' => 'foo_bar_event',
                    'payload' => ['foo' => 'bar']
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
        $event = OuterEvent::createFromArray(['event_type' => 'foo', 'payload' => ['foo' => 'payload']]);

        $this->assertSame('foo', $event->getEventType());
        $this->assertSame(['foo' => 'payload'], $event->getPayload());
    }
}
