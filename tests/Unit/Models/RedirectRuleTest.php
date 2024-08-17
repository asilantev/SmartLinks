<?php

namespace Tests\Unit\Models;

use App\Models\ConditionType;
use App\Models\RedirectRule;
use App\Models\RuleCondition;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ConditionType::factory()->create(['code' => 'platform']);
        ConditionType::factory()->create(['code' => 'browser']);
    }

    public function test_create_redirect_rule()
    {
        $smartLink = SmartLink::factory()->create();

        $redirectRule = RedirectRule::factory()->create([
            'smart_link_id' => $smartLink->id,
            'target_url' => 'https://example.com/target',
            'priority' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('redirect_rules', [
            'id' => $redirectRule->id,
            'smart_link_id' => $smartLink->id,
            'target_url' => 'https://example.com/target',
            'priority' => 1,
            'is_active' => true,
        ]);

        $this->assertInstanceOf(RedirectRule::class, $redirectRule);
        $this->assertEquals('https://example.com/target', $redirectRule->target_url);
        $this->assertEquals(1, $redirectRule->priority);
        $this->assertTrue($redirectRule->is_active);
    }

    public function test_update_redirect_rule()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);

        $redirectRule->update([
            'target_url' => 'https://updated-example.com',
            'priority' => 2,
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('redirect_rules', [
            'id' => $redirectRule->id,
            'target_url' => 'https://updated-example.com',
            'priority' => 2,
            'is_active' => false,
        ]);
    }

    public function test_delete_redirect_rule()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $redirectRule->delete();

        $this->assertDatabaseMissing('redirect_rules', [
            'id' => $redirectRule->id,
        ]);
    }

    public function test_redirect_rule_smart_link_relationship()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);

        $this->assertInstanceOf(SmartLink::class, $redirectRule->smartLink);
        $this->assertEquals($smartLink->id, $redirectRule->smartLink->id);
    }

    public function test_redirect_rule_conditions_relationship()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $condition1 = RuleCondition::factory()->create(['rule_id' => $redirectRule->id]);
        $condition2 = RuleCondition::factory()->create(['rule_id' => $redirectRule->id]);

        $this->assertCount(2, $redirectRule->conditions);
        $this->assertInstanceOf(RuleCondition::class, $redirectRule->conditions->first());
        $this->assertTrue($redirectRule->conditions->contains($condition1));
        $this->assertTrue($redirectRule->conditions->contains($condition2));
    }

    public function test_redirect_rule_is_active_attribute()
    {
        $smartLink = SmartLink::factory()->create();

        $activeRule = RedirectRule::factory()->create(['is_active' => true, 'smart_link_id' => $smartLink->id]);
        $inactiveRule = RedirectRule::factory()->create(['is_active' => false, 'smart_link_id' => $smartLink->id]);

        $this->assertTrue($activeRule->is_active);
        $this->assertFalse($inactiveRule->is_active);
    }

    public function test_redirect_rule_priority_ordering()
    {
        $smartLink = SmartLink::factory()->create();

        $rule1 = RedirectRule::factory()->create(['priority' => 2, 'smart_link_id' => $smartLink->id]);
        $rule2 = RedirectRule::factory()->create(['priority' => 1, 'smart_link_id' => $smartLink->id]);
        $rule3 = RedirectRule::factory()->create(['priority' => 3, 'smart_link_id' => $smartLink->id]);

        $orderedRules = RedirectRule::orderBy('priority')->get();

        $this->assertEquals($rule2->id, $orderedRules[0]->id);
        $this->assertEquals($rule1->id, $orderedRules[1]->id);
        $this->assertEquals($rule3->id, $orderedRules[2]->id);
    }

}
