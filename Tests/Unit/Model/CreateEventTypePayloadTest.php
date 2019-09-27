<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use Akeneo\Pim\Enrichment\Component\Product\Model\AbstractProduct;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Trilix\EventsApiBundle\Model\CreateEventTypePayload;
use Trilix\EventsApiBundle\Model\GenericEventInterface;

class CreateEventTypePayloadTest extends TestCase
{
    /** @var SerializerInterface|NormalizerInterface|MockObject */
    private $serializer;

    protected function setUp()
    {
        parent::setUp();
        $this->serializer = $this->getMockBuilder(NormalizerInterface::class)->getMock();
    }

    /**
     * @test
     * @expectedException \Trilix\EventsApiBundle\Model\PayloadCanNotBeCreatedException
     */
    public function throwsPayloadCanNotBeCreatedExceptionIfEntityCanNotBeSerialized(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new class {};
            }

        };
        $this->serializer->expects($this->once())->method('normalize')
            ->with($this->isType(IsType::TYPE_OBJECT))
            ->willThrowException(new class extends RuntimeException implements SerializerExceptionInterface {});
        (new CreateEventTypePayload($this->serializer))->__invoke($event);
    }

    /**
     * @test
     */
    public function createsPayload(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new class extends AbstractProduct {};
            }

        };
        $expectedPayload = ['foo' => 'bar'];
        $this->serializer->expects($this->once())->method('normalize')
            ->with($this->isType(IsType::TYPE_OBJECT))
            ->willReturn($expectedPayload);
        $actualPayload = (new CreateEventTypePayload($this->serializer))->__invoke($event);

        $this->assertEquals($expectedPayload, $actualPayload);
    }
}
