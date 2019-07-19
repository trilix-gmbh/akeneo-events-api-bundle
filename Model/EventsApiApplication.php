<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;

class EventsApiApplication
{
    /** @var string */
    private $code;

    /** @var string */
    private $requestUrl;

    /**
     * EventsApiApplication constructor.
     * @param string $code
     * @param string $requestUrl
     */
    public function __construct(string $code, string $requestUrl)
    {
        Assert::that($requestUrl)->url();

        $this->code = $code;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }
}
