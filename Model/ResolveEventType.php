<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Throwable;
use Trilix\EventsApiBundle\EventType\EventType;
use Trilix\EventsApiBundle\EventType\EventTypeConfigurationInterface;

class ResolveEventType
{
    /** @var EventTypeConfigurationList */
    private $eventTypeConfigurationList;

    /**
     * ResolveEventType constructor.
     * @param EventTypeConfigurationList $eventTypeConfigurationList
     */
    public function __construct(EventTypeConfigurationList $eventTypeConfigurationList)
    {
        $this->eventTypeConfigurationList = $eventTypeConfigurationList;
    }

    /**
     * @param GenericEventInterface $event
     * @return EventType
     */
    public function __invoke(GenericEventInterface $event): EventType
    {
        $entity = $event->getSubject();

        Assert::that($entity)->isObject();

        $resolvedEventTypeConfigurations = array_filter(
            $this->eventTypeConfigurationList->getIterator()->getArrayCopy(),
            function (EventTypeConfigurationInterface $eventTypeConfiguration) use ($event) {
                return ($eventTypeConfiguration->getResolver())($event);
            }
        );

        /** @var EventTypeConfigurationInterface $eventTypeConfiguration */
        $eventTypeConfiguration = array_shift($resolvedEventTypeConfigurations);

        if (!$eventTypeConfiguration) {
            throw self::throwIsNotSupportedException($event);
        }

        return new EventType($eventTypeConfiguration->getName(), ($eventTypeConfiguration->getFactory())($event));
    }

    /**
     * @param GenericEventInterface $event
     * @param Throwable|null $previous
     * @return IsNotSupportedEventException
     */
    private static function throwIsNotSupportedException(
        GenericEventInterface $event,
        Throwable $previous = null
    ): IsNotSupportedEventException {
        return new IsNotSupportedEventException(
            sprintf(
                'Given event is not supported (event=%s; subject=%s).',
                get_class($event),
                get_class($event->getSubject())
            ),
            0,
            $previous
        );
    }
}
