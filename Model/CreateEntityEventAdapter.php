<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Symfony\Component\EventDispatcher\GenericEvent;

class CreateEntityEventAdapter extends GenericEvent implements GenericCreateEntityEventInterface
{
    /**
     * CreateGenericEventAdapter constructor.
     * @param $subject
     */
    private function __construct(object $subject)
    {
        Assert::that($subject)->isObject();
        parent::__construct($subject);
    }

    public static function createFromGenericEvent(GenericEvent $genericEvent): self
    {
        return new self($genericEvent->getSubject());
    }
}
