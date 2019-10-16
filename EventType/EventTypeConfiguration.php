<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\EventType;

use Trilix\EventsApiBundle\Model\GenericEventInterface;

final class EventTypeConfiguration implements EventTypeConfigurationInterface
{
    /** @var string */
    private $name;

    /** @var callable */
    private $resolver;

    /** @var callable */
    private $payloadFactory;

    /**
     * EventTypeConfiguration constructor.
     * @param string $name
     * @param callable $resolver
     * @param callable $payloadFactory
     */
    public function __construct(string $name, callable $resolver, callable $payloadFactory)
    {
        $this->name = $name;
        $this->resolver = $resolver;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(GenericEventInterface $event): EventType
    {
        if (!($this->resolver)($event)) {
            throw new EventIsNotSupportedException(
                sprintf(
                    'Event (with subject %s) is not supported by this event type configuration (%s)',
                    get_class($event->getSubject()),
                    get_class($this)
                )
            );
        }

        return new EventType($this->name, ($this->payloadFactory)($event));
    }
}
