<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Job;

use Akeneo\Tool\Component\Batch\Model\StepExecution;
use Akeneo\Tool\Component\Connector\Step\TaskletInterface;
use Trilix\EventsApiBundle\HttpClient\Exception as HttpClientException;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\Model\EventsApiApplicationProviderInterface;

class DeliverOuterEventToConsumerTasklet implements TaskletInterface
{
    /** @var EventsApiApplicationProviderInterface */
    private $eventsApiApplicationProvider;

    /** @var HttpClientFactoryInterface */
    private $httpClientFactory;

    /** @var StepExecution */
    private $stepExecution;

    /**
     * DeliverOuterEventToConsumerTasklet constructor.
     * @param EventsApiApplicationProviderInterface $eventsApiApplicationProvider
     * @param HttpClientFactoryInterface $httpClientFactory
     */
    public function __construct(
        EventsApiApplicationProviderInterface $eventsApiApplicationProvider,
        HttpClientFactoryInterface $httpClientFactory
    ) {
        $this->eventsApiApplicationProvider = $eventsApiApplicationProvider;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution): void
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): void
    {
        $application = $this->eventsApiApplicationProvider->retrieve();
        $event = $this->stepExecution->getJobParameters()->get('event');
        $client = $this->httpClientFactory->create($application->getRequestUrl());
        try {
            $client->send(json_encode($event));
        } catch (HttpClientException $e) {
            // TODO log
            throw $e;
        }
    }
}
