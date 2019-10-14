<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Assert\Assert;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;

class IFTTTWebHooksTransportFactory implements TransportFactoryInterface
{
    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /**
     * IFTTTWebHooksTransportFactory constructor.
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
        Assert::that($options['request_url'])->notEmpty();

        $requestUrl = str_replace('{event}', 'te_st', $options['request_url']);
        Assert::that($requestUrl)->url();

        return new IFTTTWebHooksTransport($options['request_url'], $this->httpClientFactory);
    }
}
