<?php

declare( strict_types=1);

namespace Trilix\EventsApiBundle\EventSubscriber;

use Akeneo\Tool\Component\StorageUtils\Event\RemoveEvent;
use Akeneo\Tool\Component\StorageUtils\StorageEvents;
use Akeneo\Tool\Component\Versioning\Model\VersionableInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Throwable;
use Trilix\EventsApiBundle\Model\CreateEntityEventAdapter;
use Trilix\EventsApiBundle\Model\GenericEventInterface;
use Trilix\EventsApiBundle\Model\EventsHandler;
use Trilix\EventsApiBundle\Model\RemoveEntityEventAdapter;
use Trilix\EventsApiBundle\Model\UpdateEntityEventAdapter;

class AkeneoStorageUtilsSubscriber implements EventSubscriberInterface
{
    /** @var EventsHandler */
    private $handler;

    /** @var array */
    private $entitiesToBeCreated;

    /**
     * AkeneoStorageUtilsSubscriber constructor.
     * @param EventsHandler $handler
     */
    public function __construct(EventsHandler $handler)
    {
        $this->handler = $handler;
        $this->entitiesToBeCreated = [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            StorageEvents::PRE_SAVE => 'preSave',
            StorageEvents::POST_SAVE => 'postSave',
            StorageEvents::POST_REMOVE => 'postRemove'
        ];
    }

    /**
     * @param GenericEvent $genericEvent
     */
    public function preSave(GenericEvent $genericEvent): void
    {
        $entity = $genericEvent->getSubject();
        $entityHash = spl_object_hash($entity);

        if (!isset($this->entitiesToBeCreated[$entityHash])) {
            if ($entity instanceof VersionableInterface && is_null($entity->getId())) {
                $this->entitiesToBeCreated[$entityHash] = true;
            }
        }
    }

    /**
     * @param GenericEvent $genericEvent
     */
    public function postSave(GenericEvent $genericEvent): void
    {
        $entity = $genericEvent->getSubject();
        $entityHash = spl_object_hash($entity);

        if (isset($this->entitiesToBeCreated[$entityHash])) {
            $event = CreateEntityEventAdapter::createFromGenericEvent($genericEvent);
        } else {
            $event = UpdateEntityEventAdapter::createFromGenericEvent($genericEvent);
        }

        $this->handleEvent($event);
    }

    /**
     * @param RemoveEvent $removeEvent
     */
    public function postRemove(RemoveEvent $removeEvent): void
    {
        $this->handleEvent(RemoveEntityEventAdapter::createFromRemoveEvent($removeEvent));
    }

    /**
     * @param GenericEventInterface $event
     */
    private function handleEvent(GenericEventInterface $event): void
    {
        try {
            $this->handler->handle($event);
        } catch (Throwable $e) {
            // TODO Log
        }
    }
}
