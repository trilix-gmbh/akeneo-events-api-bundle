<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Akeneo\Tool\Component\StorageUtils\Event\RemoveEvent;
use Assert\Assert;
use Symfony\Component\EventDispatcher\GenericEvent;

class RemoveEntityEventAdapter extends GenericEvent implements GenericRemoveEntityEventInterface
{
    /**
     * RemoveEventAdapter constructor.
     * @param $subject
     */
    public function __construct($subject)
    {
        Assert::that($subject)->isObject();
        parent::__construct($subject);
    }

    public static function createFromRemoveEvent(RemoveEvent $removeEvent): self
    {
        return new self($removeEvent->getSubject());
    }
}
