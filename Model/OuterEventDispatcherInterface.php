<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

interface OuterEventDispatcherInterface
{
    /**
     * @param OuterEvent $event
     */
    public function dispatch(OuterEvent $event): void;
}
