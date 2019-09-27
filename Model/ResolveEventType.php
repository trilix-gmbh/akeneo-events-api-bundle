<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
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
     * @return EventType|null
     * @throws PayloadCanNotBeCreatedException
     */
    public function __invoke(GenericEventInterface $event): ?EventType
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
            return null;
        }

        return new EventType($eventTypeConfiguration->getName(), ($eventTypeConfiguration->getFactory())($event));
    }
}
