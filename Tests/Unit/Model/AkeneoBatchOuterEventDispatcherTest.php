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

    protected function setUp()
    {
        parent::setUp();
        $this->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $this->jobInstanceRepository = $this->getMockBuilder(IdentifiableObjectRepositoryInterface::class)->getMock();
        $this->jobLauncher = $this->getMockBuilder(JobLauncherInterface::class)->getMock();

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
        $this->tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue(null));
        $this->jobInstanceRepository->expects($this->once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->will($this->returnValue(new JobInstance()));
        $this->jobLauncher->expects($this->never())->method('launch');

        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar']);

        $this->dispatcher->dispatch($outerEvent);
    }

    /**
     * @test
     */
    public function notLaunchesJobIfJobInstanceIsNotSetup(): void
    {
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();

        $this->tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($token));
        $this->jobInstanceRepository->expects($this->once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->will($this->returnValue(null));
        $this->jobLauncher->expects($this->never())->method('launch');

        $outerEvent = new OuterEvent('foo_bar_event', ['foo' => 'bar']);

        $this->dispatcher->dispatch($outerEvent);
    }

    /**
     * @test
     */
    public function launchesJob(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->expects($this->once())->method('getUser')->will($this->returnValue($user));
        $jobInstance = new JobInstance();

        $this->tokenStorage->expects($this->once())->method('getToken')->will($this->returnValue($token));
        $this->jobInstanceRepository->expects($this->once())->method('findOneByIdentifier')
            ->with('deliver_outer_event_to_consumer')->will($this->returnValue($jobInstance));

        $outerEvent = new OuterEvent('foo', ['foo' => 'bar']);

        $this->jobLauncher->expects($this->once())->method('launch')
            ->with($jobInstance, $user, ['outer_event' => ['event_type' => 'foo', 'payload' => ['foo' => 'bar']]]);

        $this->dispatcher->dispatch($outerEvent);
    }
}
