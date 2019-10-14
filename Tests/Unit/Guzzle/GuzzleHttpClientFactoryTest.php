<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Guzzle;

use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\Guzzle\GuzzleHttpClientAdapter;
use Trilix\EventsApiBundle\Guzzle\GuzzleHttpClientFactory;

class GuzzleHttpClientFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider baseUriDataProvider
     * @param string $baseUri
     */
    public function throwsExceptionIfBaseUriIsInvalid(string $baseUri): void
    {
        $this->expectException(InvalidArgumentException::class);

        $factory = new GuzzleHttpClientFactory();
        $factory->create($baseUri);
    }

    /**
     * @test
     */
    public function createsGuzzleHttpClientAdapter(): void
    {
        $factory = new GuzzleHttpClientFactory();
        $client = $factory->create('http://127.0.0.1');

        $this->assertInstanceOf(GuzzleHttpClientAdapter::class, $client);
    }

    public function baseUriDataProvider(): array
    {
        return [
            [''],
            ['foo_host']
        ];
    }
}
