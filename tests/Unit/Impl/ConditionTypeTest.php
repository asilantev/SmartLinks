<?php

namespace Tests\Unit\Impl;

use App\Impl\ConditionType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\ConditionType as ConditionTypeModel;
use Tests\TestCase;

class ConditionTypeTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetCodeReturnsCodeAsString()
    {
        $code = 'test_code';
        $model = ConditionTypeModel::factory()->make(['code' => $code]);
        $conditionType = new ConditionType($model);
        $result = $conditionType->getCode();
        $this->assertEquals($code, $result);
    }

    public function testGetCodeReturnsEmptyStringWhenCodeIsNull()
    {
        $model = ConditionTypeModel::factory()->make(['code' => null]);
        $conditionType = new ConditionType($model);
        $result = $conditionType->getCode();
        $this->assertEquals('', $result);
    }

    public function testGetCodeReturnsEmptyStringWhenCodeIsEmpty()
    {
        $model = ConditionTypeModel::factory()->make(['code' => '']);
        $conditionType = new ConditionType($model);
        $result = $conditionType->getCode();
        $this->assertEquals('', $result);
    }
}
