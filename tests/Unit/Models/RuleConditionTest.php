<?php

namespace Tests\Unit\Models;

use App\Models\ConditionType;
use App\Models\RedirectRule;
use App\Models\RuleCondition;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RuleConditionTest extends TestCase
{
    use DatabaseTransactions;

    public function testFillableAttributes()
    {
        $ruleCondition = new RuleCondition();
        $fillable = ['rule_id', 'condition_type_id', 'condition_value', 'external_id'];

        $this->assertEquals($fillable, $ruleCondition->getFillable());
    }

    public function testJsonCasting()
    {
        $ruleCondition = new RuleCondition();
        $casts = $ruleCondition->getCasts();

        $this->assertArrayHasKey('condition_value', $casts);
        $this->assertEquals('json', $casts['condition_value']);
    }

    public function testTimestampsAreDisabled()
    {
        $ruleCondition = new RuleCondition();

        $this->assertFalse($ruleCondition->timestamps);
    }

    public function testRuleRelationship()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $conditionType = ConditionType::factory()->create();
        $ruleCondition = RuleCondition::factory()->create(['rule_id' => $redirectRule->id, 'condition_type_id' => $conditionType->id]);

        $this->assertInstanceOf(RedirectRule::class, $ruleCondition->rule);
        $this->assertEquals($redirectRule->id, $ruleCondition->rule->id);
        $this->assertInstanceOf(ConditionType::class, $ruleCondition->conditionType);
        $this->assertEquals($conditionType->id, $ruleCondition->conditionType->id);
    }

    public function testCreateRuleCondition()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $conditionType = ConditionType::factory()->create();

        $data = [
            'rule_id' => $redirectRule->id,
            'condition_type_id' => $conditionType->id,
            'condition_value' => ['key' => 'value'],
            'external_id' => 1
        ];

        $ruleCondition = RuleCondition::create($data);

        $this->assertInstanceOf(RuleCondition::class, $ruleCondition);
        $this->assertDatabaseHas('rule_conditions', [
            'rule_id' => $redirectRule->id,
            'condition_type_id' => $conditionType->id,
            'external_id' => 1
        ]);
        $this->assertEquals(['key' => 'value'], $ruleCondition->condition_value);
    }

    public function testUpdateRuleCondition()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $conditionType = ConditionType::factory()->create();
        $ruleCondition = RuleCondition::factory()->create(['rule_id' => $redirectRule->id, 'condition_type_id' => $conditionType->id]);

        $updatedData = [
            'condition_value' => ['updated_key' => 'updated_value'],
            'external_id' => 1
        ];

        $ruleCondition->update($updatedData);

        $this->assertDatabaseHas('rule_conditions', [
            'id' => $ruleCondition->id,
            'external_id' => 1
        ]);
        $this->assertEquals(['updated_key' => 'updated_value'], $ruleCondition->fresh()->condition_value);
    }

    public function testDeleteRuleCondition()
    {
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        $conditionType = ConditionType::factory()->create();
        $ruleCondition = RuleCondition::factory()->create(['rule_id' => $redirectRule->id, 'condition_type_id' => $conditionType->id]);

        $ruleCondition->delete();

        $this->assertDatabaseMissing('rule_conditions', ['id' => $ruleCondition->id]);
    }
}
