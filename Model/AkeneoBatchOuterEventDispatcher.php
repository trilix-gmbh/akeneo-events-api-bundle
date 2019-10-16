<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Model;

use Akeneo\Tool\Bundle\BatchBundle\Launcher\JobLauncherInterface;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Trilix\EventsApiBundle\Job\JobParameters\DeliverOuterEventConstraintCollectionProvider;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

class AkeneoBatchOuterEventDispatcher implements OuterEventDispatcherInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var IdentifiableObjectRepositoryInterface */
    private $jobInstanceRepository;

    /** @var JobLauncherInterface */
    private $jobLauncher;

    /**
     * AkeneoBatchOuterEventDispatcher constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param IdentifiableObjectRepositoryInterface $jobInstanceRepository
     * @param JobLauncherInterface $jobLauncher
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        IdentifiableObjectRepositoryInterface $jobInstanceRepository,
        JobLauncherInterface $jobLauncher
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->jobInstanceRepository = $jobInstanceRepository;
        $this->jobLauncher = $jobLauncher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(OuterEvent $event): void
    {
        $token = $this->tokenStorage->getToken();
        $jobInstance = $this->jobInstanceRepository->findOneByIdentifier('deliver_outer_event_to_consumer');
        if (!$token || !$jobInstance) {
            return;
        }

        $this->jobLauncher
            ->launch(
                $jobInstance,
                $token->getUser(),
                [DeliverOuterEventConstraintCollectionProvider::JOB_PARAMETER_KEY_OUTER_EVENT => $event->toArray()]
            );
    }
}
