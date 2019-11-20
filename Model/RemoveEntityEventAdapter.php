<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Akeneo\Tool\Component\StorageUtils\Event\RemoveEvent;
use Assert\Assert;
use Symfony\Component\EventDispatcher\GenericEvent;

class RemoveEntityEventAdapter extends GenericEvent implements GenericRemoveEntityEventInterface
{
    /**
     * RemoveEntityEventAdapter constructor.
     * @param object $subject
     */
    private function __construct(object $subject)
    {
        parent::__construct($subject);
    }

    public static function createFromRemoveEvent(RemoveEvent $removeEvent): self
    {
        Assert::that($removeEvent->getSubject())->isObject();
        return new self($removeEvent->getSubject());
    }
}
