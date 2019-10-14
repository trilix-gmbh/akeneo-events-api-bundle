<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Transport;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\Transport\IFTTTWebHooksTransport;
use Trilix\EventsApiBundle\Transport\IFTTTWebHooksTransportFactory;

class IFTTTWebHooksTransportFactoryTest extends TestCase
{
    private const IFTTT_WEBHOOKS_REQUEST_URL = 'https://maker.ifttt.com/trigger/{event}/with/key/f48alXOMKZopZMCRG-I5HWbHQYvAtm4v9lHUoVkDAnu';

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
        $factory = new IFTTTWebHooksTransportFactory($httpClientFactory);

        $factory->create($options);
    }

    /**
     * @test
     */
    public function createsIFTTTWebHooksTransport(): void
    {
        /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
        $httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        $factory = new IFTTTWebHooksTransportFactory($httpClientFactory);

        $transport = $factory->create(['request_url' => self::IFTTT_WEBHOOKS_REQUEST_URL]);

        $this->assertInstanceOf(IFTTTWebHooksTransport::class, $transport);
    }

    public function invalidOptionsDataProvider(): array
    {
        return [
            [[]],
            [['foo']],
            [['foo' => 'bar']],
            [['request_url' => '']],
            [['request_url' => 'url_value']]
        ];
    }
}
