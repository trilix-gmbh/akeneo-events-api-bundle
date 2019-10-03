<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Job\JobParameters;

use Akeneo\Tool\Component\Batch\Job\JobInterface;
use Akeneo\Tool\Component\Batch\Job\JobParameters\ConstraintCollectionProviderInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;

class DeliverOuterEventConstraintCollectionProvider implements ConstraintCollectionProviderInterface
{
    const JOB_PARAMETER_KEY_OUTER_EVENT_JSON = 'outer_event_json';

    /**
     * {@inheritdoc}
     */
    public function getConstraintCollection(): Collection
    {
        return new Collection(['fields' => [self::JOB_PARAMETER_KEY_OUTER_EVENT_JSON => new Optional()]]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(JobInterface $job): bool
    {
        return 'deliver_outer_event_to_consumer' === $job->getName();
    }
}
