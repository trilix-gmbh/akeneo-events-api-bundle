<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

interface TransportFactoryInterface
{
    /**
     * @param array $options
     * @return Transport
     */
    public function create(array $options): Transport;
}
