<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Symfony\Component\EventDispatcher\GenericEvent;

class CreateEntityEventAdapter extends GenericEvent implements GenericCreateEntityEventInterface
{
    /**
     * CreateEntityEventAdapter constructor.
     * @param object $subject
     */
    private function __construct(object $subject)
    {
        parent::__construct($subject);
    }

    public static function createFromGenericEvent(GenericEvent $genericEvent): self
    {
        Assert::that($genericEvent->getSubject())->isObject();
        return new self($genericEvent->getSubject());
    }
}
