<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use Assert\InvalidArgumentException as AssertionInvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Trilix\EventsApiBundle\EventType\EventType;
use Trilix\EventsApiBundle\Model\ResolveEventType;
use Trilix\EventsApiBundle\Model\GenericEventInterface;
use Trilix\EventsApiBundle\Model\EventsHandler;
use Trilix\EventsApiBundle\Model\PayloadCanNotBeCreatedException;
use Trilix\EventsApiBundle\Model\OuterEventDispatcherInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;
use Trilix\EventsApiBundle\OuterEvent\OuterEventBuilder;

class EventsHandlerTest extends TestCase
{
    /** @var ResolveEventType|MockObject */
    private $resolver;

    /** @var OuterEventBuilder|MockObject $builder */
    private $builder;

    /** @var OuterEventDispatcherInterface|MockObject $dispatcher */
    private $dispatcher;

    /** @var EventsHandler */
    private $handler;

    /** @var LoggerInterface|MockObject */
    private $logger;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = $this->createMock(ResolveEventType::class);
        $this->builder = $this->createMock(OuterEventBuilder::class);
        $this->dispatcher = $this->createMock(OuterEventDispatcherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->handler = new EventsHandler($this->resolver, $this->builder, $this->dispatcher, $this->logger);
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionIfGenericEventSubjectIsNotObject(): void
    {
        $this->expectException(AssertionInvalidArgumentException::class);

        $this->handler->handle(
            new class implements GenericEventInterface {
                public function getSubject()
                {
                    return 'string';
                }
            }
        );
    }

    /**
     * @test
     */
    public function stopsHandlingIfEventTypeWasNotResolved(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new Subject();
            }
        };

        $this->resolver->expects(self::once())->method('__invoke')
            ->with($event)->willReturn(null);

        $this->builder->expects(self::never())->method('withPayload');
        $this->builder->expects(self::never())->method('build');

        $this->dispatcher->expects(self::never())->method('dispatch');

        $this->logger->expects(self::never())->method('notice');

        $this->handler->handle($event);
    }

    /**
     * @test
     */
    public function catchesPayloadCanNotBeCreatedException(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new Subject();
            }
        };

        $this->resolver->expects(self::once())->method('__invoke')
            ->with($event)->willThrowException(new PayloadCanNotBeCreatedException('testMessage'));

        $this->logger->expects(self::once())
            ->method('notice')
            ->with('testMessage');

        $this->handler->handle($event);
    }

    /**
     * @test
     */
    public function passesThrownExceptionNext(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new Subject();
            }
        };

        $this->resolver->expects(self::once())->method('__invoke')
            ->with($event)->willThrowException(new RuntimeException('testMessage'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('testMessage');

        $this->logger->expects(self::never())
            ->method('notice');

        $this->handler->handle($event);
    }

    /**
     * @test
     */
    public function handlesEvent(): void
    {
        $event = new class implements GenericEventInterface {
            public function getSubject()
            {
                return new Subject();
            }
        };

        $payload = ['foo' => 'bar'];
        $eventType = new EventType('test_outer_event', $payload);
        $outerEvent = new OuterEvent('test_outer_event', ['foo' => 'bar'], time());

        $this->resolver->expects(self::once())->method('__invoke')->with($event)->willReturn($eventType);

        $this->builder->expects(self::once())->method('withPayload')
            ->with($payload)->willReturnSelf();
        $this->builder->expects(self::once())->method('build')
            ->with('test_outer_event')->willReturn($outerEvent);

        $this->dispatcher->expects(self::once())->method('dispatch')->with($outerEvent);

        $this->logger->expects(self::never())
            ->method('notice');

        $this->handler->handle($event);
    }
}

class Subject {}
