<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\Model\EventsApiApplication;

class EventsApiApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function objectInitialization(): void
    {
        $application = new EventsApiApplication('foo', 'http://foo.com');

        $this->assertEquals('foo', $application->getCode());
        $this->assertEquals('http://foo.com', $application->getRequestUrl());
    }
}
