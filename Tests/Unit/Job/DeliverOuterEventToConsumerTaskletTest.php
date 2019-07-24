<?php

declare(strict_types=1);

namespace Trilix\EventsApiBundle\Tests\Unit\Job;

use Akeneo\Tool\Component\Batch\Job\JobParameters;
use Akeneo\Tool\Component\Batch\Model\JobExecution;
use Akeneo\Tool\Component\Batch\Model\StepExecution;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Trilix\EventsApiBundle\HttpClient\Exception as HttpClientException;
use Trilix\EventsApiBundle\HttpClient\HttpClientFactoryInterface;
use Trilix\EventsApiBundle\HttpClient\HttpClientInterface;
use Trilix\EventsApiBundle\Job\DeliverOuterEventToConsumerTasklet;
use Trilix\EventsApiBundle\Model\EventsApiApplication;
use Trilix\EventsApiBundle\Model\EventsApiApplicationProviderInterface;

class DeliverOuterEventToConsumerTaskletTest extends TestCase
{
    /** @var EventsApiApplicationProviderInterface|MockObject $applicationProvider */
    private $applicationProvider;

    /** @var HttpClientFactoryInterface|MockObject $httpClientFactory */
    private $httpClientFactory;

    /** @var LoggerInterface|MockObject */
    private $logger;

    /** @var DeliverOuterEventToConsumerTasklet */
    private $tasklet;

    protected function setUp()
    {
        parent::setUp();
        $this->applicationProvider = $this->getMockBuilder(EventsApiApplicationProviderInterface::class)->getMock();
        $this->httpClientFactory = $this->getMockBuilder(HttpClientFactoryInterface::class)->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $application = new EventsApiApplication('foo', 'http://bar.com');

        $this->applicationProvider->expects($this->once())->method('retrieve')->will($this->returnValue($application));

        $this->tasklet = new DeliverOuterEventToConsumerTasklet(
            $this->applicationProvider,
            $this->httpClientFactory,
            $this->logger
        );
    }

    /**
     * @test
     */
    public function executes(): void
    {
        /** @var HttpClientInterface|MockObject $httpClient */
        $httpClient = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $event = ['foo'];

        $this->httpClientFactory->expects($this->once())->method('create')
            ->with('http://bar.com')->will($this->returnValue($httpClient));

        $httpClient->expects($this->once())->method('send')
            ->with($this->isJson())->will($this->returnValue($response));

        $this->tasklet->setStepExecution($this->createStepExecution(new JobParameters(['event' => $event])));

        $this->tasklet->execute();
    }

    /**
     * @test
     */
    public function expectedExceptionIsRaised(): void
    {
        /** @var HttpClientInterface|MockObject $httpClient */
        $httpClient = $this->getMockBuilder(HttpClientInterface::class)->getMock();

        $event = ['foo'];

        $this->httpClientFactory->expects($this->once())->method('create')
            ->with('http://bar.com')->will($this->returnValue($httpClient));

        $httpClient->expects($this->once())->method('send')
            ->with($this->isJson())->will($this->throwException(new HttpClientException('testMessage')));

        $this->tasklet->setStepExecution($this->createStepExecution(new JobParameters(['event' => $event])));

        $this->logger->expects($this->once())->method('error')
            ->with('testMessage');

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('testMessage');

        $this->tasklet->execute();
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
