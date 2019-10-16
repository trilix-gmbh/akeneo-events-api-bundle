<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Guzzle;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Trilix\EventsApiBundle\HttpClient\Exception as HttpClientException;

class GuzzleHttpClientAdapter implements ClientInterface
{
    /** @var \GuzzleHttp\ClientInterface */
    private $guzzleHttpClient;

    /**
     * HttpClientAdapter constructor.
     * @param \GuzzleHttp\ClientInterface $guzzleHttpClient
     */
    public function __construct(\GuzzleHttp\ClientInterface $guzzleHttpClient)
    {
        $this->guzzleHttpClient = $guzzleHttpClient;
    }

    /**
     * {@inheritdoc}
     * @throws HttpClientException
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->guzzleHttpClient->send($request);
        } catch (GuzzleException $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
