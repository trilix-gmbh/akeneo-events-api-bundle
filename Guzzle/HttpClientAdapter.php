<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Guzzle;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Trilix\EventsApiBundle\HttpClient\Exception as HttpClientException;
use Trilix\EventsApiBundle\HttpClient\HttpClientInterface;

class HttpClientAdapter implements HttpClientInterface
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
     */
    public function send(string $body, array $options = []): ResponseInterface
    {
        try {
            $request = new Request('POST', new Uri(), ['Content-Type' => 'application/json'], $body);
            return $this->guzzleHttpClient->send($request, $options);
        } catch (GuzzleException $ge) {
            throw new HttpClientException($ge->getMessage(), $ge->getCode(), $ge);
        }
    }
}
