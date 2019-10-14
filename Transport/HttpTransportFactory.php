<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Assert\Assert;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;

class HttpTransportFactory implements TransportFactoryInterface
{
    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /**
     * HttpTransportFactory constructor.
     * @param HttpClientFactoryInterface $httpClientFactory
     */
    public function __construct(HttpClientFactoryInterface $httpClientFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): Transport
    {
        Assert::that($options)->keyExists('request_url');
        Assert::that($options['request_url'])->notEmpty()->url();

        return new HttpTransport($options['request_url'], $this->httpClientFactory);
    }
}
