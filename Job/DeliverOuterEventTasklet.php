<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Job;

use Akeneo\Tool\Component\Batch\Model\StepExecution;
use Akeneo\Tool\Component\Connector\Step\TaskletInterface;
use Trilix\EventsApiBundle\Job\JobParameters\DeliverOuterEventConstraintCollectionProvider;
use Trilix\EventsApiBundle\Model\EventsApiApplicationProviderInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;
use Trilix\EventsApiBundle\Transport\Transport;

class DeliverOuterEventTasklet implements TaskletInterface
{
    /** @var EventsApiApplicationProviderInterface */
    private $eventsApiApplicationProvider;

    /** @var Transport */
    private $transport;

    /** @var StepExecution */
    private $stepExecution;

    /**
     * DeliverOuterEventToConsumerTasklet constructor.
     * @param EventsApiApplicationProviderInterface $eventsApiApplicationProvider
     * @param Transport $transport
     */
    public function __construct(
        EventsApiApplicationProviderInterface $eventsApiApplicationProvider,
        Transport $transport
    ) {
        $this->eventsApiApplicationProvider = $eventsApiApplicationProvider;
        $this->transport = $transport;
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
        $outerEventJson = $this->stepExecution->getJobParameters()
            ->get(DeliverOuterEventConstraintCollectionProvider::JOB_PARAMETER_KEY_OUTER_EVENT);

        $this->transport->deliver($application, OuterEvent::createFromArray($outerEventJson));
    }
}
