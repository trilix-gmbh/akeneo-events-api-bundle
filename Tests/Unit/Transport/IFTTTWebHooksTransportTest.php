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
use Trilix\EventsApiBundle\Transport\IFTTTWebHooksTransport;

class IFTTTWebHooksTransportTest extends TestCase
{
    /**
     * @test
     */
    public function deliversOuterEventToIFTTTWebHooks(): void
    {
        $outerEvent = new OuterEvent('foo_name', ['foo' => 'payload']);
        $requestUrl = 'https://iftt.com/a/b/{event}/x/y';

        /** @var ClientInterface|MockObject $httpClient */
        $httpClient = $this->getMockBuilder(ClientInterface::class)->getMock();
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();

        $httpClientFactory->expects($this->once())->method('create')
            ->with('https://iftt.com/a/b/foo_name/x/y')->willReturn($httpClient);
        $requestFactory->expects($this->once())->method('create')
            ->with('POST', '', ['Content-Type' => 'application/json'], json_encode(['value1' => 'foo_name', 'value2' => ['foo' => 'payload']]))
            ->willReturn($request);
        $httpClient->expects($this->once())->method('sendRequest')->with($request);

        $transport = new IFTTTWebHooksTransport($requestUrl, $httpClientFactory, $requestFactory);

        $transport->deliver($outerEvent);
    }
}
