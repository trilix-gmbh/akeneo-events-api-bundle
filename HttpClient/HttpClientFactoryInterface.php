<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\HttpClient;

use Psr\Http\Client\ClientInterface;

interface HttpClientFactoryInterface
{
    const DEFAULT_TIMEOUT = 4;

    /**
     * @param string $baseUri
     * @param int $timeout in seconds
     * @return ClientInterface
     */
    public function create(string $baseUri, int $timeout = self::DEFAULT_TIMEOUT): ClientInterface;
}
