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

    /** @var int */
    private $eventTime;

    /**
     * OuterEvent constructor.
     * @param string $eventType
     * @param array $payload
     * @param int $eventTime
     */
    public function __construct(string $eventType, array $payload, int $eventTime)
    {
        Assert::that($eventType)->notEmpty();
        Assert::that($eventTime)->lessOrEqualThan(time());

        $this->eventType = $eventType;
        $this->payload = $payload;
        $this->eventTime = $eventTime;
    }

    /**
     * @return string
     */
    public function eventType(): string
    {
        return $this->eventType;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }

    /**
     * @return int
     */
    public function eventTime(): int
    {
        return $this->eventTime;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->eventType,
            'payload' => $this->payload,
            'event_time' => $this->eventTime
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
    public static function fromArray(array $array): self
    {
        Assert::that($array)
            ->keyExists('event_type')
            ->keyExists('payload')
            ->keyExists('event_time');

        Assert::that($array['event_type'])->string();
        Assert::that($array['payload'])->isArray();
        Assert::that($array['event_time'])->integer();

        $payload = array_map(static function($element) {
            if ((is_array($element)) && empty($element)) {
                return (object) $element;
            }
            return $element;
        }, $array['payload']);

        return new self($array['event_type'], $payload, $array['event_time']);
    }
}
