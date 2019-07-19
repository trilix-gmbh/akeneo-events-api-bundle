<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\OuterEvent;

class OuterEventBuilder
{
    /** @var array */
    private $payload = [];

    public function withPayload(array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param string $eventName
     * @return OuterEvent
     */
    public function build(string $eventName): OuterEvent
    {
        $outerEvent = new OuterEvent($eventName, $this->payload);

        $this->payload = [];

        return $outerEvent;
    }
}
