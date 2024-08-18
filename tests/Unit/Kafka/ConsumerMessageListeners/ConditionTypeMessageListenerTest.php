<?php

namespace Tests\Unit\Kafka\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Listeners\ConsumerMessageListeners\ConditionTypeMessageListener;
use App\Models\ConditionType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class ConditionTypeMessageListenerTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandlerWithDelete()
    {
        $listener = new ConditionTypeMessageListener();

        $conditionType = ConditionType::create([
            'external_id' => 1,
            'code' => 'code_123',
            'name' => 'Some name',
        ]);

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getBody')
            ->andReturn(['id' => 1, 'deleted' => true]);

        $listener->handler($messageMock);

        $this->assertDatabaseMissing('condition_types', ['id' => $conditionType->id]);
    }

    public function testHandlerWithUpdateOrCreate()
    {
        $listener = new ConditionTypeMessageListener();

        // Mock the event and message
        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getBody')
            ->andReturn([
                'id' => 2,
                'deleted' => false,
                'data' => [
                    'code' => 'code_234',
                    'name' => 'Updated name',
                ]
            ]);

        $listener->handler($messageMock);

        $this->assertDatabaseHas('condition_types', [
            'external_id' => 2,
            'code' => 'code_234',
            'name' => 'Updated name',
        ]);
    }

    public function testShouldQueueReturnsTrueForValidTopic()
    {
        $listener = new ConditionTypeMessageListener();

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getTopicName')
            ->andReturn(TopicNameEnum::ConditionType->name);

        $result = $listener->shouldQueue($messageMock);

        $this->assertTrue($result);
    }

    public function testShouldQueueReturnsFalseForInvalidTopic()
    {
        $listener = new ConditionTypeMessageListener();

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getTopicName')
            ->andReturn('different_topic');

        $result = $listener->shouldQueue($messageMock);

        $this->assertFalse($result);
    }
}
