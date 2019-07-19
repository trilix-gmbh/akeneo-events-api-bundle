<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use ArrayIterator;
use IteratorAggregate;
use Trilix\EventsApiBundle\EventType\EventTypeConfigurationInterface;

class EventTypeConfigurationList implements IteratorAggregate
{
    /** @var EventTypeConfigurationInterface[] */
    private $eventTypeConfigurations;

    /**
     * @param EventTypeConfigurationInterface $eventTypeConfiguration
     */
    public function addEventTypeConfiguration(EventTypeConfigurationInterface $eventTypeConfiguration): void
    {
        $this->eventTypeConfigurations[] = $eventTypeConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->eventTypeConfigurations);
    }
}
