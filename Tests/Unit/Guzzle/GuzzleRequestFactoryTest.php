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

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('POST', $request->getMethod());
        $this->assertContains('foo', $request->getHeader('bar'));
        $this->assertSame('', $request->getUri()->getPath());
        $this->assertSame('foo_bar', $request->getBody()->getContents());
    }
}
