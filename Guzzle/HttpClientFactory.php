<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Guzzle;

use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\HttpClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(string $baseUri, int $timeout = self::DEFAULT_TIMEOUT): HttpClientInterface
    {
        return new HttpClientAdapter(new \GuzzleHttp\Client(['base_uri' => $baseUri, 'timeout' => $timeout]));
    }
}
