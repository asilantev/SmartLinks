<?php

namespace Tests\Unit\Kafka;

use App\Console\Commands\KafkaConsumer;
use App\Interfaces\Broker\ConsumerInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class KafkaConsumerTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandleCallsConsumeMethodOnConsumer()
    {
        $consumerMock = Mockery::mock(ConsumerInterface::class);
        $consumerMock->shouldReceive('consume')->once();
        $command = new KafkaConsumer();
        $command->handle($consumerMock);
    }

    public function testCommandSignature()
    {
        $command = new KafkaConsumer();
        $this->assertEquals('broker:consume', $command->getName());
    }

    public function testCommandDescription()
    {
        $command = new KafkaConsumer();
        $this->assertEquals('Consume messages from broker', $command->getDescription());
    }
}
