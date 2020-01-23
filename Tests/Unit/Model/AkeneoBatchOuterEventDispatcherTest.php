<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Model;

use Akeneo\Tool\Bundle\BatchBundle\Launcher\JobLauncherInterface;
use Akeneo\Tool\Component\Batch\Model\JobInstance;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Trilix\EventsApiBundle\Model\AkeneoBatchOuterEventDispatcher;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;

class AkeneoBatchOuterEventDispatcherTest extends TestCase
{
    /** @var TokenStorageInterface|MockObject */
    private $tokenStorage;

    /** @var IdentifiableObjectRepositoryInterface|MockObject */
    private $jobInstanceRepository;

    /** @var JobLauncherInterface|MockObject */
    private $jobLauncher;

    /** @var AkeneoBatchOuterEventDispatcher */
    private $dispatcher;

    /**
     * @throws \ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->jobInstanceRepository = $this->createMock(IdentifiableObjectRepositoryInterface::class);
        $this->jobLauncher = $this->createMock(JobLauncherInterface::class);

        $this->dispatcher = new AkeneoBatchOuterEventDispatcher(
            $this->tokenStorage,
            $this->jobInstanceRepository,
            $this->jobLauncher
        );
    }

    /**
     * @test
     */
    public function notLaunchesJobIfTokenIsMissing(): void
    {
        $this->tokenStorage->expects(self::once())->method('getToken')->willReturn(null);
        $this->jobInstanceRepository->expects(self::once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->willReturn(new JobInstance());
        $this->jobLauncher->expects(self::never())->method('launch');

        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar'], time());

        $this->dispatcher->dispatch($outerEvent);
    }

    /**
     * @test
     */
    public function notLaunchesJobIfJobInstanceIsNotSetup(): void
    {
        $token = $this->createMock(TokenInterface::class);

        $this->tokenStorage->expects(self::once())->method('getToken')->willReturn($token);
        $this->jobInstanceRepository->expects(self::once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->willReturn(null);
        $this->jobLauncher->expects(self::never())->method('launch');

        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar'], time());

        $this->dispatcher->dispatch($outerEvent);
    }

    /**
     * @test
     */
    public function launchesJob(): void
    {
        $user = $this->createMock(UserInterface::class);
        $token = $this->createMock(TokenInterface::class);
        $token->expects(self::once())->method('getUser')->willReturn($user);
        $jobInstance = new JobInstance();

        $this->tokenStorage->expects(self::once())->method('getToken')->willReturn($token);
        $this->jobInstanceRepository->expects(self::once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->willReturn($jobInstance);

        $eventTime = time();
        $outerEvent = new OuterEvent('foo', ['foo' => 'bar'], $eventTime);

        $this->jobLauncher->expects(self::once())->method('launch')
            ->with(
                $jobInstance,
                $user,
                ['outer_event' => ['event_type' => 'foo', 'payload' => ['foo' => 'bar'], 'event_time' => $eventTime]]
            );

        $this->dispatcher->dispatch($outerEvent);
    }
}
