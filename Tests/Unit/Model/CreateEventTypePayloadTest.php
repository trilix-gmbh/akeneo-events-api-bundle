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
use Trilix\EventsApiBundle\Model\PayloadCanNotBeCreatedException;

class CreateEventTypePayloadTest extends TestCase
{
    /** @var SerializerInterface|NormalizerInterface|MockObject */
    private $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = $this->createMock(NormalizerInterface::class);
    }

    /**
     * @test
     */
    public function throwsPayloadCanNotBeCreatedExceptionIfEntityCanNotBeSerialized(): void
    {
        $this->expectException(PayloadCanNotBeCreatedException::class);

        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new class {};
            }

        };
        $this->serializer->expects(self::once())->method('normalize')
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
        $this->serializer->expects(self::once())->method('normalize')
            ->with($this->isType(IsType::TYPE_OBJECT))
            ->willReturn($expectedPayload);
        $actualPayload = (new CreateEventTypePayload($this->serializer))->__invoke($event);

        self::assertSame($expectedPayload, $actualPayload);
    }
}
