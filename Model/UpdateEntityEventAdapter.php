<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Assert\Assert;
use Symfony\Component\EventDispatcher\GenericEvent;

class UpdateEntityEventAdapter extends GenericEvent implements GenericUpdateEntityEventInterface
{
    /**
     * UpdateGenericEventAdapter constructor.
     * @param $subject
     */
    public function __construct($subject)
    {
        Assert::that($subject)->isObject();
        parent::__construct($subject);
    }

    public static function createFromGenericEvent(GenericEvent $genericEvent): self
    {
        return new self($genericEvent->getSubject());
    }
}
