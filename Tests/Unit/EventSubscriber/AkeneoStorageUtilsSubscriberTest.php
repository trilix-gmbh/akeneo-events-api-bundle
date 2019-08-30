<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\EventSubscriber;

use Akeneo\Tool\Component\StorageUtils\Event\RemoveEvent;
use Akeneo\Tool\Component\Versioning\Model\VersionableInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Trilix\EventsApiBundle\EventSubscriber\AkeneoStorageUtilsSubscriber;
use Trilix\EventsApiBundle\Model\GenericCreateEntityEventInterface;
use Trilix\EventsApiBundle\Model\EventsHandler;
use Trilix\EventsApiBundle\Model\GenericRemoveEntityEventInterface;
use Trilix\EventsApiBundle\Model\GenericUpdateEntityEventInterface;

class AkeneoStorageUtilsSubscriberTest extends TestCase
{
    /** @var AkeneoStorageUtilsSubscriber */
    private $subscriber;

    /** @var EventsHandler|MockObject */
    private $eventsHandler;

    /** @var LoggerInterface|MockObject */
    private $logger;

    protected function setUp()
    {
        parent::setUp();
        $this->eventsHandler = $this->getMockBuilder(EventsHandler::class)
            ->disableOriginalConstructor()->getMock();

        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->subscriber = new AkeneoStorageUtilsSubscriber($this->eventsHandler, $this->logger);
    }

    /**
     * @test
     */
    public function newEntityIdentifiedAndMarked(): void
    {
        $subject = new VersionableObject(null);

        $preSaveEvent = new GenericEvent($subject, ['foo' => 'bar']);
        $postSaveEvent = new GenericEvent($subject, ['bar' => 'foo']);

        $this->eventsHandler->expects($this->once())->method('handle')
            ->with(
                $this->logicalAnd(
                    $this->isInstanceOf(GenericCreateEntityEventInterface::class),
                    $this->callback(
                        function (GenericCreateEntityEventInterface $event) use ($subject): bool {
                            return $event->getSubject() === $subject;
                        }
                    )
                )
            );

        $this->subscriber->preSave($preSaveEvent);

        $subject->setId(8);

        $this->subscriber->postSave($postSaveEvent);
    }

    /**
     * @test
     */
    public function existingEntityDoesNotMarked(): void
    {
        $subject = new VersionableObject(4);

        $preSaveEvent = new GenericEvent($subject, ['foo' => 'bar']);
        $postSaveEvent = new GenericEvent($subject, ['bar' => 'foo']);

        $this->eventsHandler->expects($this->once())->method('handle')
            ->with(
                $this->logicalAnd(
                    $this->isInstanceOf(GenericUpdateEntityEventInterface::class),
                    $this->callback(
                        function (GenericUpdateEntityEventInterface $event) use ($subject): bool {
                            return $event->getSubject() === $subject;
                        }
                    )
                )
            );

        $this->subscriber->preSave($preSaveEvent);
        $this->subscriber->postSave($postSaveEvent);
    }

    /**
     * @test
     */
    public function handlesStorageRemoveEvent(): void
    {
        $subject = new VersionableObject(7);

        $this->eventsHandler->expects($this->once())->method('handle')
            ->with(
                $this->logicalAnd(
                    $this->isInstanceOf(GenericRemoveEntityEventInterface::class),
                    $this->callback(
                        function (GenericRemoveEntityEventInterface $event) use ($subject): bool {
                            return $event->getSubject() === $subject;
                        }
                    )
                )
            );

        $this->subscriber->postRemove(new RemoveEvent($subject, 7, ['foo' => 'bar']));
    }

    /**
     * @test
     */
    public function expectedExceptionIsCatchedDuringPostSave(): void
    {
        $subject = new VersionableObject(4);

        $postSaveEvent = new GenericEvent($subject, ['bar' => 'foo']);

        $this->eventsHandler->expects($this->once())->method('handle')
            ->willThrowException(new \Exception('testMessage'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('testMessage');

        $this->subscriber->postSave($postSaveEvent);
    }

    /**
     * @test
     */
    public function expectedExceptionIsCatchedDuringPostRemove(): void
    {
        $subject = new VersionableObject(7);

        $this->eventsHandler->expects($this->once())->method('handle')
            ->willThrowException(new \Exception('testMessage'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('testMessage');

        $this->subscriber->postRemove(new RemoveEvent($subject, 7, ['foo' => 'bar']));
    }
}

class VersionableObject implements VersionableInterface
{
    /** @var int|null */
    private $id;

    /**
     * VersionableObject constructor.
     * @param int|null $id
     */
    public function __construct(?int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
