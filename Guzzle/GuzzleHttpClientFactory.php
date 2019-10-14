<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Guzzle;

use Assert\Assert;
use GuzzleHttp\Client as GuzzleHttpClient;
use Psr\Http\Client\ClientInterface;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;

class GuzzleHttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(string $baseUri, int $timeout = self::DEFAULT_TIMEOUT): ClientInterface
    {
        Assert::that($baseUri)
            ->notEmpty()
            ->url();

        return new GuzzleHttpClientAdapter(new GuzzleHttpClient(['base_uri' => $baseUri, 'timeout' => $timeout]));
    }
}
