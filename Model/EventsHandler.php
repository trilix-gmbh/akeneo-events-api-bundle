<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Psr\Log\LoggerInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEventBuilder;

class EventsHandler
{
    /** @var ResolveEventType */
    private $resolveEventType;

    /** @var OuterEventBuilder */
    private $outerEventBuilder;

    /** @var OuterEventDispatcherInterface */
    private $outerEventDispatcher;

    /** @var LoggerInterface */
    private $logger;

    /**
     * EventsHandler constructor.
     * @param ResolveEventType $resolveEventType
     * @param OuterEventBuilder $outerEventBuilder
     * @param OuterEventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResolveEventType $resolveEventType,
        OuterEventBuilder $outerEventBuilder,
        OuterEventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->resolveEventType = $resolveEventType;
        $this->outerEventBuilder = $outerEventBuilder;
        $this->outerEventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * @param GenericEventInterface $event
     */
    public function handle(GenericEventInterface $event): void
    {
        $entity = $event->getSubject();
        Assert::that($entity)->isObject();

        try {
            $eventType = $this->resolveEventType->__invoke($event);
            $outerEvent = $this->outerEventBuilder
                ->withPayload($eventType->getPayload())
                ->build($eventType->getName());
            $this->outerEventDispatcher->dispatch($outerEvent);
        } catch (IsNotSupportedEventException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
