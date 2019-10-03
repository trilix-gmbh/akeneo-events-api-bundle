<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\EventType;

use Trilix\EventsApiBundle\Model\GenericEventInterface;

interface EventTypeConfigurationInterface
{
    /**
     * @param GenericEventInterface $event
     * @return EventType
     * @throws EventIsNotSupportedException
     */
    public function resolve(GenericEventInterface $event): EventType;
}
