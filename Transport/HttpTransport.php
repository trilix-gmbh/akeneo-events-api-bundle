<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\Model\EventsApiApplication;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

class HttpTransport implements Transport
{
    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /**
     * HttpTransport constructor.
     * @param HttpClientFactoryInterface $httpClientFactory
     */
    public function __construct(HttpClientFactoryInterface $httpClientFactory)
    {
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function deliver(EventsApiApplication $application, OuterEvent $event): void
    {
        $client = $this->httpClientFactory->create($application->getRequestUrl());
        $client->send(json_encode($event));
    }
}
