<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\EventType;

class EventType
{
    /** @var string */
    private $name;

    /** @var array */
    private $payload;

    /**
     * EventType constructor.
     * @param string $name
     * @param array $payload
     */
    public function __construct(string $name, array $payload = [])
    {
        $this->name = $name;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
