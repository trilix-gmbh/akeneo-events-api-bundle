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

        $this->assertEquals('foo_bar_event', $outerEvent->getEventType());
        $this->assertEquals(['foo' => 'bar'], $outerEvent->getPayload());
    }

    /**
     * @test
     */
    public function outerEventSerializesIntoJson(): void
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
}
