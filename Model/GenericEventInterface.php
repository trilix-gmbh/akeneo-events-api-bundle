<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

interface GenericEventInterface
{
    public function getSubject();
}
