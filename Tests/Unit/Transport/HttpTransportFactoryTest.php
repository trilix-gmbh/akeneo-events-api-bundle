<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Transport;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\RequestFactoryInterface;
use Trilix\EventsApiBundle\Transport\HttpTransport;
use Trilix\EventsApiBundle\Transport\HttpTransportFactory;

class HttpTransportFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidOptionsDataProvider
     * @param array $options
     */
    public function throwsExceptionIfOptionsAreInvalid(array $options): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $factory = new HttpTransportFactory($httpClientFactory, $requestFactory);

        $factory->create($options);
    }

    /**
     * @test
     */
    public function createsHttpTransport(): void
    {
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        /** @var RequestFactoryInterface|MockObject $requestFactory */
        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $factory = new HttpTransportFactory($httpClientFactory, $requestFactory);

        $transport = $factory->create(['request_url' => 'http://foo.bar']);

        $this->assertInstanceOf(HttpTransport::class, $transport);
    }

    public function invalidOptionsDataProvider(): array
    {
        return [
            [ [] ],
            [ ['foo_param'] ],
            [ ['foo_param' => 'value'] ],
            [ ['request_url' => ''] ],
            [ ['request_url' => 'url_value'] ],
        ];
    }
}
