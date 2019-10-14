<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Transport;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\HttpClientInterface;
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

        /** @var HttpClientInterface|MockObject $httpClient */
        $httpClient = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();

        $httpClientFactory->expects($this->once())->method('create')
            ->with('https://iftt.com/a/b/foo_name/x/y')->willReturn($httpClient);
        $httpClient->expects($this->once())->method('send')
            ->with(
                json_encode([
                    'value1' => 'foo_name',
                    'value2' => ['foo' => 'payload']
                ])
            );

        $transport = new IFTTTWebHooksTransport($requestUrl, $httpClientFactory);

        $transport->deliver($outerEvent);
    }
}
