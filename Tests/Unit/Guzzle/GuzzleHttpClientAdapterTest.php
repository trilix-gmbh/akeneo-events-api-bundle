<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Guzzle;

use GuzzleHttp\ClientInterface as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Trilix\EventsApiBundle\Guzzle\GuzzleHttpClientAdapter;
use Trilix\EventsApiBundle\HttpClient\Exception as HttpClientException;

class GuzzleHttpClientAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function throwsHttpClientExceptionIfGuzzleExceptionHasBeenThrown(): void
    {
        $this->expectException(HttpClientException::class);

        /** @var RequestInterface|MockObject $request */
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();

        /** @var GuzzleHttpClient|MockObject $guzzleClient */
        $guzzleClient = $this->getMockBuilder(GuzzleHttpClient::class)->getMock();
        $guzzleClient->expects($this->once())->method('send')
            ->with($request)->willThrowException(new class extends RuntimeException implements GuzzleException {});

        $adapter = new GuzzleHttpClientAdapter($guzzleClient);
        $adapter->sendRequest($request);
    }

    /**
     * @test
     */
    public function guzzleHttpClientSendsGivenRequest(): void
    {
        /** @var RequestInterface|MockObject $request */
        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        /** @var GuzzleHttpClient|MockObject $guzzleClient */
        $guzzleClient = $this->getMockBuilder(GuzzleHttpClient::class)->getMock();
        $guzzleClient->expects($this->once())->method('send')
            ->with($request)->willReturn($response);

        $adapter = new GuzzleHttpClientAdapter($guzzleClient);
        $this->assertSame($response, $adapter->sendRequest($request));
    }
}
