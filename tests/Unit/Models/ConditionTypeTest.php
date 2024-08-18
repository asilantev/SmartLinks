<?php

namespace Tests\Unit\Models;

use App\Models\ConditionType;
use App\Models\RedirectRule;
use App\Models\RuleCondition;
use App\Models\SmartLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ConditionTypeTest extends TestCase
{
    use DatabaseTransactions;

    public function testFillableAttributes()
    {
        $conditionType = new ConditionType();
        $fillable = ['code', 'name', 'external_id'];

        $this->assertEquals($fillable, $conditionType->getFillable());
    }

    public function testConditionsRelationship()
    {
        $conditionType = ConditionType::factory()->create();
        $smartLink = SmartLink::factory()->create();
        $redirectRule = RedirectRule::factory()->create(['smart_link_id' => $smartLink->id]);
        RuleCondition::factory()->count(3)->create(['rule_id' => $redirectRule->id, 'condition_type_id' => $conditionType->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $conditionType->conditions());
        $this->assertCount(3, $conditionType->conditions);
        $this->assertInstanceOf(RuleCondition::class, $conditionType->conditions->first());
    }

    public function testCreateConditionType()
    {
        $data = [
            'code' => 'browser',
            'name' => 'Browser Type',
            'external_id' => 1
        ];

        $conditionType = ConditionType::create($data);

        $this->assertInstanceOf(ConditionType::class, $conditionType);
        $this->assertDatabaseHas('condition_types', $data);
    }

    public function testUpdateConditionType()
    {
        $conditionType = ConditionType::factory()->create();

        $updatedData = [
            'code' => 'updated_code',
            'name' => 'Updated Name',
            'external_id' => 1
        ];

        $conditionType->update($updatedData);

        $this->assertDatabaseHas('condition_types', $updatedData);
    }

    public function testDeleteConditionType()
    {
        $conditionType = ConditionType::factory()->create();

        $conditionType->delete();

        $this->assertDatabaseMissing('condition_types', ['id' => $conditionType->id]);
    }
}
