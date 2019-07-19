<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\OuterEvent;

use Assert\Assert;
use JsonSerializable;

class OuterEvent implements JsonSerializable
{
    /** @var string */
    private $type;

    /** @var array */
    private $payload;

    /**
     * OuterEvent constructor.
     * @param string $type
     * @param array $payload
     */
    public function __construct(string $type, array $payload)
    {
        Assert::that($type)->notBlank();

        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
    public function jsonSerialize()
    {
        return [
            'event_type' => $this->type,
            'payload' => $this->payload
        ];
    }
}
