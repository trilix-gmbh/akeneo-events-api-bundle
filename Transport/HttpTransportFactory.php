<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Assert\Assert;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\RequestFactoryInterface;

class HttpTransportFactory implements TransportFactoryInterface
{
    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /**
     * HttpTransportFactory constructor.
     * @param HttpClientFactoryInterface $httpClientFactory
     * @param RequestFactoryInterface $requestFactory
     */
    public function __construct(HttpClientFactoryInterface $httpClientFactory, RequestFactoryInterface $requestFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): Transport
    {
        Assert::that($options)->keyExists('request_url');
        Assert::that($options['request_url'])->notEmpty()->url();

        return new HttpTransport($options['request_url'], $this->httpClientFactory, $this->requestFactory);
    }
}
