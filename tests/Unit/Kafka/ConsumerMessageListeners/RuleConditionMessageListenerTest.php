<?php

namespace Tests\Unit\Kafka\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Listeners\ConsumerMessageListeners\RuleConditionMessageListener;
use App\Models\ConditionType;
use App\Models\RedirectRule;
use App\Models\RuleCondition;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class RuleConditionMessageListenerTest extends TestCase
{
    use DatabaseTransactions;

    private RuleConditionMessageListener $listener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listener = new RuleConditionMessageListener();
    }

    public function testHandlerCreatesRuleCondition()
    {
        $smartLink = SmartLink::factory()->create(['external_id' => 1]);
        $redirectRule = RedirectRule::factory()->create(['external_id' => 1, 'smart_link_id' => $smartLink->id]);
        $conditionType = ConditionType::factory()->create(['external_id' => 1]);

        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => false,
            'data' => [
                'rule_id' => 1,
                'condition_type_id' => 1,
                'condition_value' => 'test-value',
            ],
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseHas('rule_conditions', [
            'external_id' => 1,
            'rule_id' => $redirectRule->id,
            'condition_type_id' => $conditionType->id,
            'condition_value' => 'test-value',
        ]);
    }

    public function testHandlerUpdatesExistingRuleCondition()
    {
        $smartLink = SmartLink::factory()->create(['external_id' => 1]);
        $oldRedirectRule = RedirectRule::factory()->create(['external_id' => 1, 'smart_link_id' => $smartLink->id]);
        $oldConditionType = ConditionType::factory()->create(['external_id' => 1, 'code' => 'time_interval']);
        $newRedirectRule = RedirectRule::factory()->create(['external_id' => 2, 'smart_link_id' => $smartLink->id]);
        $newConditionType = ConditionType::factory()->create(['external_id' => 2, 'code' => 'platform']);

        RuleCondition::factory()->create([
            'external_id' => 1,
            'rule_id' => $oldRedirectRule->id,
            'condition_type_id' => $oldConditionType->id,
            'condition_value' => 'old-value',
        ]);

        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => false,
            'data' => [
                'rule_id' => 2,
                'condition_type_id' => 2,
                'condition_value' => 'new-value',
            ],
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseHas('rule_conditions', [
            'external_id' => 1,
            'rule_id' => $newRedirectRule->id,
            'condition_type_id' => $newConditionType->id,
            'condition_value' => 'new-value',
        ]);
    }

    public function testHandlerDeletesRuleCondition()
    {
        $conditionType = ConditionType::create([
            'external_id' => 1,
            'code' => 'code_123',
            'name' => 'Some name',
        ]);
        $smartLink = SmartLink::factory()->create(['external_id' => 1]);
        $redirectRule = RedirectRule::factory()->create(['external_id' => 1, 'smart_link_id' => $smartLink->id]);

        RuleCondition::factory()->create([
            'rule_id' => $redirectRule->id,
            'external_id' => 1,
        ]);

        $message = $this->createMockMessage([
            'id' => 1,
            'deleted' => true,
        ]);

        $event = new ConsumerMessageEvent($message);

        $this->listener->handler($event);

        $this->assertDatabaseMissing('rule_conditions', [
            'external_id' => 1,
        ]);
    }

    public function testShouldQueueReturnsTrue()
    {
        $message = $this->createMockMessage([], TopicNameEnum::RuleCondition->name);
        $event = new ConsumerMessageEvent($message);

        $this->assertTrue($this->listener->shouldQueue($event));
    }

    public function testShouldQueueReturnsFalse()
    {
        $message = $this->createMockMessage([], 'other-topic');
        $event = new ConsumerMessageEvent($message);

        $this->assertFalse($this->listener->shouldQueue($event));
    }

    private function createMockMessage(array $body, string $topicName = TopicNameEnum::RuleCondition->name)
    {
        $message = Mockery::mock(ConsumerMessageInterface::class);
        $message->shouldReceive('getBody')->andReturn($body);
        $message->shouldReceive('getTopicName')->andReturn($topicName);
        return $message;
    }
}
