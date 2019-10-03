<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Trilix\EventsApiBundle\EventType\EventIsNotSupportedException;
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

        $eventType = null;
        $iterator = $this->eventTypeConfigurationList->getIterator();
        $iterator->rewind();
        while (is_null($eventType) && $iterator->valid() && $configuration = $iterator->current()) {
            try {
                /** @var EventTypeConfigurationInterface $configuration */
                $eventType = $configuration->resolve($event);
            } catch (EventIsNotSupportedException $e) {
            } finally {
                $iterator->next();
            }
        }

        return $eventType;
    }
}
