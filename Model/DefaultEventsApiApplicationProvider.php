<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

class DefaultEventsApiApplicationProvider implements EventsApiApplicationProviderInterface
{
    private const APPLICATION_CODE = 'default';

    /** @var string */
    private $uri;

    /**
     * DefaultEventsApiApplicationProvider constructor.
     * @param string|null $uri
     */
    public function __construct(?string $uri)
    {
        $this->uri = (string) $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(): EventsApiApplication
    {
        if (0 === strlen($this->uri)) {
            throw new EventsApiApplicationIsNotConfiguredException(
                'Events API Default Application is not configured.'
            );
        }

        $application = new EventsApiApplication(
            self::APPLICATION_CODE,
            $this->uri
        );

        return $application;
    }
}
