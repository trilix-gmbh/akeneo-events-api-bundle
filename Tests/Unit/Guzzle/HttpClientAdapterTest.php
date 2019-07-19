<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Guzzle;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Trilix\EventsApiBundle\Guzzle\HttpClientAdapter;

class HttpClientAdapterTest extends TestCase
{
    /** @var HttpClientAdapter */
    private $adapter;

    /** @var ClientInterface|MockObject */
    private $guzzleClient;

    protected function setUp()
    {
        parent::setUp();
        $this->guzzleClient = $this->getMockBuilder(ClientInterface::class)->getMock();

        $this->adapter = new HttpClientAdapter($this->guzzleClient);
    }

    /**
     * @test
     */
    public function sendsBody(): void
    {
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $this->guzzleClient->expects($this->once())->method('send')
            ->with(
                $this->logicalAnd(
                    $this->isInstanceOf(RequestInterface::class),
                    $this->callback(function (RequestInterface $request): bool {
                        return $request->getMethod() === 'POST' && (string) $request->getBody() === 'foo_body'
                            && in_array('application/json', $request->getHeader('Content-Type'), true);
                    })
                )
            )
            ->will($this->returnValue($response));

        $this->adapter->send('foo_body', ['foo' => 'bar']);
    }

    /**
     * @test
     * @expectedException \Trilix\EventsApiBundle\HttpClient\Exception
     */
    public function throwsHttpClientExceptionIfGuzzleThrowsOne(): void
    {
        $this->guzzleClient->expects($this->once())->method('send')
            ->with($this->anything())
            ->will($this->throwException(new class extends RuntimeException implements GuzzleException {}));

        $this->adapter->send('foo_body', ['foo' => 'bar']);
    }
}
