<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use Akeneo\Pim\Enrichment\Component\Product\Model\AbstractProduct;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\EventType\EventTypeConfigurationInterface;
use Trilix\EventsApiBundle\Model\EventTypeConfigurationList;
use Trilix\EventsApiBundle\Model\ResolveEventType;
use Trilix\EventsApiBundle\Model\GenericEventInterface;

class ResolveEventTypeTest extends TestCase
{
    /**
     * @test
     */
    public function resolvesEventType(): void
    {
        $eventTypeConfigurationList = new EventTypeConfigurationList();
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function getName(): string { return 'bar_event'; }
                public function getResolver(): callable { return function () { return false; }; }
                public function getFactory(): callable { return function () { return ['bar_payload_body']; }; }
            }
        );
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function getName(): string { return 'foo_event'; }
                public function getResolver(): callable
                {
                    return function (GenericEventInterface $event) {
                        return $event->getSubject() instanceof isSupportedEntity;
                    };
                }
                public function getFactory(): callable
                {
                    return function () {
                        return ['bar' => 'is_supported'];
                    };
                }
            }
        );

        $eventType = (new ResolveEventType($eventTypeConfigurationList))
            ->__invoke(
                new class implements GenericEventInterface {
                    public function getSubject()
                    {
                        return new isSupportedEntity();
                    }
                }
            );

        $this->assertEquals('foo_event', $eventType->getName());
        $this->assertNotEmpty($eventType->getPayload());
        $this->assertArrayHasKey('bar', $eventType->getPayload());
        $this->assertEquals('is_supported', $eventType->getPayload()['bar']);
    }

    /**
     * @test
     */
    public function returnsNullIfEventTypeWasNotResolved(): void
    {
        $eventTypeConfigurationList = new EventTypeConfigurationList();
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function getName(): string { return 'foo_event'; }
                public function getResolver(): callable {
                    return function (GenericEventInterface $event) {
                        return $event->getSubject() instanceof isSupportedEntity;
                    };
                }
                public function getFactory(): callable { return function () { return ['bar' => 'is_supported']; }; }
            }
        );

        $eventType = (new ResolveEventType($eventTypeConfigurationList))
            ->__invoke(
                new class implements GenericEventInterface {
                    public function getSubject()
                    {
                        return new isNotSupportedEntity();
                    }
                }
            );

        $this->assertNull($eventType);
    }
}

class isNotSupportedEntity {
}

class isSupportedEntity extends AbstractProduct {
}
