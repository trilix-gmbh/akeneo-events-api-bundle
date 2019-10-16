<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

interface Transport
{
    /**
     * @param OuterEvent $event
     */
    public function deliver(OuterEvent $event): void;
}
