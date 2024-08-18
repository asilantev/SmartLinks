<?php

namespace Tests\Unit\Kafka\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Listeners\ConsumerMessageListeners\RedirectRuleMessageListener;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class RedirectRuleMessageListenerTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testHandlerWithDelete()
    {
        $listener = new RedirectRuleMessageListener();

        $smartLink = SmartLink::create([
            'external_id' => 1,
            'slug' => 'slug',
            'default_url' => 'http://example.com',
        ]);

        $redirectRule = RedirectRule::create([
            'external_id' => 1,
            'smart_link_id' => $smartLink->id,
            'target_url' => 'http://example.com',
            'is_active' => true,
            'priority' => 1,
        ]);

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getBody')
            ->andReturn(['id' => 1, 'deleted' => true]);

        $listener->handler($messageMock);

        $this->assertDatabaseMissing('redirect_rules', ['external_id' => 1]);
    }

    public function testHandlerWithUpdateOrCreate()
    {
        $listener = new RedirectRuleMessageListener();

        $smartLink = SmartLink::create([
            'external_id' => 1,
            'slug' => 'slug',
            'default_url' => 'http://example.com',
        ]);

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getBody')
            ->andReturn([
                'id' => 1,
                'deleted' => false,
                'data' => [
                    'smart_link_id' => 1,
                    'target_url' => 'http://changed.com',
                    'is_active' => false,
                    'priority' => 2
                ]
            ]);

        $listener->handler($messageMock);

        $this->assertDatabaseHas('redirect_rules', [
            'external_id' => 1,
            'smart_link_id' => $smartLink->id,
            'target_url' => 'http://changed.com',
            'is_active' => false,
            'priority' => 2,
        ]);
    }

    public function testShouldQueueReturnsTrueForValidTopic()
    {
        $listener = new RedirectRuleMessageListener();

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getTopicName')
            ->andReturn(TopicNameEnum::RedirectRule->name);

        $result = $listener->shouldQueue($messageMock);

        $this->assertTrue($result);
    }

    public function testShouldQueueReturnsFalseForInvalidTopic()
    {
        $listener = new RedirectRuleMessageListener();

        $messageMock = Mockery::mock(ConsumerMessageEvent::class);
        $messageMock->message = Mockery::mock(ConsumerMessageInterface::class);
        $messageMock->message->shouldReceive('getTopicName')
            ->andReturn('different_topic');

        $result = $listener->shouldQueue($messageMock);

        $this->assertFalse($result);
    }
}
