<?php

namespace Tests\Unit\Kafka\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Listeners\ConsumerMessageListeners\SmartLinkMessageListener;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SmartLinkMessageListenerTest extends TestCase
{
    use DatabaseTransactions;

    private SmartLinkMessageListener $listener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listener = new SmartLinkMessageListener();
    }

    public function testHandlerCreatesSmartLink()
    {
        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => false,
            'data' => [
                'slug' => 'test-slug',
                'default_url' => 'http://example.com',
                'expires_at' => '2023-01-01 00:00:00',
            ],
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseHas('smart_links', [
            'external_id' => 1,
            'slug' => 'test-slug',
            'default_url' => 'http://example.com',
            'expires_at' => '2023-01-01 00:00:00',
        ]);
    }

    public function testHandlerUpdatesExistingSmartLink()
    {
        SmartLink::factory()->create([
            'external_id' => 1,
            'slug' => 'old-slug',
            'default_url' => 'http://old-example.com',
            'expires_at' => '2022-01-01 00:00:00',
        ]);

        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => false,
            'data' => [
                'slug' => 'new-slug',
                'default_url' => 'http://new-example.com',
                'expires_at' => '2023-01-01 00:00:00',
            ],
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseHas('smart_links', [
            'external_id' => 1,
            'slug' => 'new-slug',
            'default_url' => 'http://new-example.com',
            'expires_at' => '2023-01-01 00:00:00',
        ]);
    }

    public function testHandlerDeletesSmartLink()
    {
        SmartLink::factory()->create([
            'external_id' => 1,
        ]);

        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => true,
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseMissing('smart_links', [
            'external_id' => 1,
        ]);
    }

    public function testShouldQueueReturnsTrue()
    {
        $message = $this->createMockMessage([], TopicNameEnum::SmartLink->name);
        $event = new ConsumerMessageEvent($message);

        $this->assertTrue($this->listener->shouldQueue($event));
    }

    public function testShouldQueueReturnsFalse()
    {
        $message = $this->createMockMessage([], 'other-topic');
        $event = new ConsumerMessageEvent($message);

        $this->assertFalse($this->listener->shouldQueue($event));
    }

    private function createMockMessage(array $body, string $topicName = TopicNameEnum::SmartLink->name)
    {
        $message = Mockery::mock(ConsumerMessageInterface::class);
        $message->shouldReceive('getBody')->andReturn($body);
        $message->shouldReceive('getTopicName')->andReturn($topicName);
        return $message;
    }

}
