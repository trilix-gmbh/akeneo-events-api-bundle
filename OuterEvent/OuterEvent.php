<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\OuterEvent;

use Assert\Assert;
use JsonSerializable;

class OuterEvent implements JsonSerializable
{
    /** @var string */
    private $eventType;

    /** @var array */
    private $payload;

    /**
     * OuterEvent constructor.
     * @param string $eventType
     * @param array $payload
     */
    public function __construct(string $eventType, array $payload)
    {
        Assert::that($eventType)->notBlank();

        $this->eventType = $eventType;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getEventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->eventType,
            'payload' => $this->payload
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param array $array
     * @return OuterEvent
     */
    public static function createFromArray(array $array): self
    {
        Assert::that($array)
            ->keyExists('event_type')
            ->keyExists('payload');

        Assert::that($array['event_type'])
            ->string()
            ->notEmpty();

        Assert::that($array['payload'])
            ->isArray()
            ->notEmpty();

        return new OuterEvent($array['event_type'], $array['payload']);
    }
}
