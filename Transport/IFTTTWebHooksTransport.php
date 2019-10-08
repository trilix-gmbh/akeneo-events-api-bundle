<?php

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
     * {@inheritdoc}
     */
    public function deliver(OuterEvent $event): void
    {
        $requestUrl = sprintf($this->requestUrl, $event->getEventType());
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
