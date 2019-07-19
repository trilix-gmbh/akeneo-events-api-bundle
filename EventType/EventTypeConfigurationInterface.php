<?php

namespace Trilix\EventsApiBundle\EventType;

interface EventTypeConfigurationInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return callable
     */
    public function getResolver(): callable;

    /**
     * @return callable
     */
    public function getFactory(): callable ;
}
