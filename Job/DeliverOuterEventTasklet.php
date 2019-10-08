<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Job;

use Akeneo\Tool\Component\Batch\Model\StepExecution;
use Akeneo\Tool\Component\Connector\Step\TaskletInterface;
use Trilix\EventsApiBundle\Job\JobParameters\DeliverOuterEventConstraintCollectionProvider;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;
use Trilix\EventsApiBundle\Transport\Transport;

class DeliverOuterEventTasklet implements TaskletInterface
{
    /** @var Transport */
    private $transport;

    /** @var StepExecution */
    private $stepExecution;

    /**
     * DeliverOuterEventToConsumerTasklet constructor.
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
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
        $outerEventJson = $this->stepExecution->getJobParameters()
            ->get(DeliverOuterEventConstraintCollectionProvider::JOB_PARAMETER_KEY_OUTER_EVENT);

        $this->transport->deliver(OuterEvent::createFromArray($outerEventJson));
    }
}
