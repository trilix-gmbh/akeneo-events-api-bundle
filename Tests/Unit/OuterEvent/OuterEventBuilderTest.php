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
    public function buildsOuterEventWithEventNameAndEmptyPayload(): void
    {
        $outerEvent = (new OuterEventBuilder())->build('foo_event');

        $this->assertEquals('foo_event', $outerEvent->getType());
        $this->assertCount(0, $outerEvent->getPayload());
    }

    /**
     * @test
     */
    public function buildsOuterEventWithPayload(): void
    {
        $payload = ['foo' => 'bar'];
        $outerEvent = (new OuterEventBuilder())
            ->withPayload($payload)
            ->build('foo_event');

        $this->assertEquals('foo_event', $outerEvent->getType());
        $this->assertEquals($payload, $outerEvent->getPayload());
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

        $this->assertEquals($payload, $outerEventWithPayload->getPayload());
        $this->assertCount(0, $outerEventWithOutPayload->getPayload());
    }
}

