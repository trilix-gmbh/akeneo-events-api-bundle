<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\EventType;

final class EventTypeConfiguration implements EventTypeConfigurationInterface
{
    /** @var string */
    private $name;

    /** @var callable */
    private $resolver;

    /** @var callable */
    private $factory;

    /**
     * EventTypeConfiguration constructor.
     * @param string $name
     * @param callable $resolver
     * @param callable $factory
     */
    public function __construct(string $name, callable $resolver, callable $factory)
    {
        $this->name = $name;
        $this->resolver = $resolver;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(): callable
    {
        return $this->resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactory(): callable
    {
        return $this->factory;
    }
}
