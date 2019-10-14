<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Transport;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\RequestFactoryInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;
use Trilix\EventsApiBundle\Transport\HttpTransport;

class HttpTransportTest extends TestCase
{
    /**
     * @test
     */
    public function deliversOuterEventViaHttpClient(): void
    {
        $outerEvent = new OuterEvent('foo_event', ['foo' => 'payload']);

        /** @var ClientInterface|MockObject $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();

        $httpClientFactory->expects($this->once())->method('create')
            ->with('http://localhost:1234')->willReturn($httpClient);
        $requestFactory->expects($this->once())->method('create')
            ->with('POST', '', ['Content-Type' => 'application/json'], json_encode($outerEvent))
            ->willReturn($request);
        $httpClient->expects($this->once())->method('sendRequest')->with($request);

        $httpTransport = new HttpTransport('http://localhost:1234', $httpClientFactory, $requestFactory);

        $httpTransport->deliver($outerEvent);
    }
}
