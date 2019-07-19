<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\Model\DefaultEventsApiApplicationProvider;

class DefaultEventsApiApplicationProviderTest extends TestCase
{
    /**
     * @test
     * @expectedException \Trilix\EventsApiBundle\Model\EventsApiApplicationIsNotConfiguredException
     */
    public function throwsExceptionIfDefaultEventsApiApplicationUriIsNotDefined(): void
    {
        $provider = new DefaultEventsApiApplicationProvider(null);
        $provider->retrieve();
    }
}
