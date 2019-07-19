<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CreateEventTypePayload
{
    /** @var SerializerInterface|NormalizerInterface */
    private $serializer;

    /**
     * CreateEventTypePayload constructor.
     * @param NormalizerInterface $serializer
     */
    public function __construct(NormalizerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GenericEventInterface $event
     * @return array
     */
    public function __invoke(GenericEventInterface $event): array
    {
        $entity = $event->getSubject();

        try {
            $payload = $this->serializer->normalize($entity, 'external_api');
        } catch (SerializerExceptionInterface $e) {
            throw new IsNotSupportedEventException(
                sprintf(
                    'Given event is not supported (event=%s; subject=%s).',
                    get_class($event),
                    get_class($entity)
                ),
                0,
                $e
            );
        }

        return $payload;
    }
}
