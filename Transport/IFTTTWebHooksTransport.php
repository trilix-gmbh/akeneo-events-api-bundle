<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Transport;

use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

class IFTTTWebHooksTransport implements Transport
{
    /** @var string */
    private $requestUrl;

    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /**
     * IFTTTWebHooksTransport constructor.
     * @param string $requestUrl
     * @param HttpClientFactoryInterface $httpClientFactory
     */
    public function __construct(string $requestUrl, HttpClientFactoryInterface $httpClientFactory)
    {
        $this->requestUrl = $requestUrl;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function deliver(OuterEvent $event): void
    {
        $requestUrl = str_replace('{event}', $event->getEventType(), $this->requestUrl);
        $client = $this->httpClientFactory->create($requestUrl);
        $client->send(
            json_encode(
                [
                    'value1' => $event->getEventType(),
                    'value2' => $event->getPayload()
                ]
            )
        );
    }
}
