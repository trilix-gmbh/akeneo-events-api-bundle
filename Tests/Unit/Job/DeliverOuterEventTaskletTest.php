<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Job;

use Akeneo\Tool\Component\Batch\Job\JobParameters;
use Akeneo\Tool\Component\Batch\Model\JobExecution;
use Akeneo\Tool\Component\Batch\Model\StepExecution;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Trilix\EventsApiBundle\Job\DeliverOuterEventTasklet;
use Trilix\EventsApiBundle\Model\EventsApiApplication;
use Trilix\EventsApiBundle\Model\EventsApiApplicationProviderInterface;
use Trilix\EventsApiBundle\OuterEvent\OuterEvent;
use Trilix\EventsApiBundle\Transport\Transport;

class DeliverOuterEventTaskletTest extends TestCase
{
    /**
     * @test
     */
    public function executes(): void
    {
        $application = new EventsApiApplication('foo', 'http://bar.com');
        $event = new OuterEvent('foo_event', ['foo' => 'payload']);

        /** @var EventsApiApplicationProviderInterface|MockObject $applicationProvider */
        $applicationProvider = $this->getMockBuilder(EventsApiApplicationProviderInterface::class)->getMock();
        $applicationProvider ->expects($this->once())->method('retrieve')
            ->will($this->returnValue($application));

        /** @var Transport|MockObject $transport */
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->expects($this->once())->method('deliver')
            ->with($this->isInstanceOf(EventsApiApplication::class), $event);

        $tasklet = new DeliverOuterEventTasklet(
            $applicationProvider,
            $transport
        );

        $tasklet->setStepExecution(
            $this->createStepExecution(new JobParameters(['outer_event' => $event->toArray()]))
        );

        $tasklet->execute();
    }

    /**
     * @param JobParameters $jobParameters
     * @return StepExecution
     */
    private function createStepExecution(JobParameters $jobParameters): StepExecution
    {
        return new StepExecution(
            'foo',
            (new JobExecution())->setJobParameters($jobParameters)
        );
    }
}
