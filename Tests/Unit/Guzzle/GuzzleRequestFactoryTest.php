<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Guzzle;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Trilix\EventsApiBundle\Guzzle\GuzzleRequestFactory;

class GuzzleRequestFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createsRequest(): void
    {
        $factory = new GuzzleRequestFactory();

        $request = $factory->create('POST', '', ['bar' => 'foo'], 'foo_bar');

        self::assertInstanceOf(RequestInterface::class, $request);
        self::assertSame('POST', $request->getMethod());
        self::assertContains('foo', $request->getHeader('bar'));
        self::assertSame('', $request->getUri()->getPath());
        self::assertSame('foo_bar', $request->getBody()->getContents());
    }
}
