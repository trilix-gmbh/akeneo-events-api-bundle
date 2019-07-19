<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\HttpClient;

interface HttpClientFactoryInterface
{
    const DEFAULT_TIMEOUT = 4;

    /**
     * @param string $baseUri
     * @param int $timeout in seconds
     * @return HttpClientInterface
     */
    public function create(string $baseUri, int $timeout = self::DEFAULT_TIMEOUT): HttpClientInterface;
}
