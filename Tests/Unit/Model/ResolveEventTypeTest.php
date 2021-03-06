<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use Akeneo\Pim\Enrichment\Component\Product\Model\AbstractProduct;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\EventType\EventIsNotSupportedException;
use Trilix\EventsApiBundle\EventType\EventType;
use Trilix\EventsApiBundle\EventType\EventTypeConfigurationInterface;
use Trilix\EventsApiBundle\Model\EventTypeConfigurationList;
use Trilix\EventsApiBundle\Model\ResolveEventType;
use Trilix\EventsApiBundle\Model\GenericEventInterface;

class ResolveEventTypeTest extends TestCase
{
    /**
     * @test
     */
    public function returnsNullIfEventTypeWasNotResolved(): void
    {
        $eventTypeConfigurationList = new EventTypeConfigurationList();
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function resolve(GenericEventInterface $event): EventType
                {
                    throw new EventIsNotSupportedException('Event is not supported');
                }
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

        self::assertNull($eventType);
    }

    /**
     * @test
     */
    public function ensureFirstlyResolvedEventTypeBeingReturned(): void
    {
        $eventTypeConfigurationList = new EventTypeConfigurationList();
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function resolve(GenericEventInterface $event): EventType
                {
                    return new EventType('bar', ['bar' => 'is_supported']);
                }
            }
        );
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function resolve(GenericEventInterface $event): EventType
                {
                    return new EventType('foo', ['foo' => 'is_supported']);
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

        self::assertSame('bar', $eventType->getName());
        self::assertNotEmpty($eventType->getPayload());
        self::assertArrayHasKey('bar', $eventType->getPayload());
        self::assertSame('is_supported', $eventType->getPayload()['bar']);
    }


    /**
     * @test
     */
    public function resolvesEventType(): void
    {
        $eventTypeConfigurationList = new EventTypeConfigurationList();
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function resolve(GenericEventInterface $event): EventType
                {
                    throw new EventIsNotSupportedException('Event is not supported');
                }
            }
        );
        $eventTypeConfigurationList->addEventTypeConfiguration(
            new class implements EventTypeConfigurationInterface {
                public function resolve(GenericEventInterface $event): EventType
                {
                    return new EventType('foo', ['foo' => 'is_supported']);
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

        self::assertSame('foo', $eventType->getName());
        self::assertNotEmpty($eventType->getPayload());
        self::assertArrayHasKey('foo', $eventType->getPayload());
        self::assertSame('is_supported', $eventType->getPayload()['foo']);
    }
}

class isNotSupportedEntity {
}

class isSupportedEntity extends AbstractProduct {
}
