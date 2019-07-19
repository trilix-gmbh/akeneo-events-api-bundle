<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

interface EventsApiApplicationProviderInterface
{
    /**
     * @return EventsApiApplication
     * @throws EventsApiApplicationIsNotConfiguredException
     */
    public function retrieve(): EventsApiApplication;
}
