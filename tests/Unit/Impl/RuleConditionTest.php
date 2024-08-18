<?php

namespace Tests\Unit\Impl;

use App\Impl\RuleCondition;
use App\Interfaces\ConditionTypeInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\RuleCondition as RuleConditionModel;
use Mockery;
use Tests\TestCase;

class RuleConditionTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetTypeReturnsConditionTypeInstance()
    {
        $conditionTypeModel = \App\Models\ConditionType::factory()->create();
        $conditionTypeInterface = Mockery::mock(ConditionTypeInterface::class);
        app()->instance(ConditionTypeInterface::class, $conditionTypeInterface);

        $ruleConditionModel = RuleConditionModel::factory()->make(['condition_type_id' => $conditionTypeModel->id]);
        $ruleCondition = new RuleCondition($ruleConditionModel);

        $result = $ruleCondition->getType();

        $this->assertInstanceOf(ConditionTypeInterface::class, $result);
    }

    public function testGetValueReturnsDecodedJsonValue()
    {
        $conditionTypeModel = \App\Models\ConditionType::factory()->create();
        $jsonData = '{"key": "value"}';
        $ruleConditionModel = RuleConditionModel::factory()->make(['condition_type_id' => $conditionTypeModel->id, 'condition_value' => $jsonData]);
        $ruleCondition = new RuleCondition($ruleConditionModel);
        $result = $ruleCondition->getValue();
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals('value', $result->key);
    }

    public function testGetValueReturnsNullWhenConditionValueIsNull()
    {
        $conditionTypeModel = \App\Models\ConditionType::factory()->create();
        $ruleConditionModel = RuleConditionModel::factory()->make(['condition_type_id' => $conditionTypeModel->id, 'condition_value' => null]);
        $ruleCondition = new RuleCondition($ruleConditionModel);
        $result = $ruleCondition->getValue();
        $this->assertNull($result);
    }
}
