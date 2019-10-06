<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Trilix\EventsApiBundle\Model\EventsApiApplication;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

interface Transport
{
    /**
     * @param EventsApiApplication $application
     * @param OuterEvent $event
     */
    public function deliver(EventsApiApplication $application, OuterEvent $event): void;
}
