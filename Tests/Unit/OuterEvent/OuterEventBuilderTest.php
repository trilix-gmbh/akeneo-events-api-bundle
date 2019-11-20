<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\OuterEvent;

use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\OuterEvent\OuterEventBuilder;

class OuterEventBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function buildsOuterEvent(): void
    {
        $outerEvent = (new OuterEventBuilder())->build('foo_event');
        self::assertSame('foo_event', $outerEvent->eventType());
    }

    /**
     * @test
     */
    public function buildsOuterEventWithEmptyPayload(): void
    {
        $outerEvent = (new OuterEventBuilder())->build('foo_event');
        self::assertEmpty($outerEvent->payload());
    }

    /**
     * @test
     */
    public function buildsOuterEventWithNotEmptyPayload(): void
    {
        $payload = ['foo' => 'bar'];
        $outerEvent = (new OuterEventBuilder())
            ->withPayload($payload)
            ->build('foo_event');

        self::assertSame($payload, $outerEvent->payload());
    }

    /**
     * @test
     */
    public function builderReleasesConfigurationAfterBuild(): void
    {
        $builder = new OuterEventBuilder();

        $payload = ['foo' => 'bar'];

        $outerEventWithPayload = $builder->withPayload($payload)->build('with_payload');
        $outerEventWithOutPayload = $builder->build('with_out_payload');

        self::assertSame($payload, $outerEventWithPayload->payload());
        self::assertEmpty($outerEventWithOutPayload->payload());
    }
}

