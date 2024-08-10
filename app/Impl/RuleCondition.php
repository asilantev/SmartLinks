<?php

namespace App\Impl;

use App\Interfaces\ConditionTypeInterface;
use App\Interfaces\RuleConditionInterface;

class RuleCondition implements RuleConditionInterface
{

    public function __construct(private \App\Models\RuleCondition $model)
    {
    }

    public function getType(): ConditionTypeInterface
    {
        return app(ConditionTypeInterface::class, [$this->model->conditionType()->first()]);
    }

    public function getValue(): ?\stdClass
    {
        return json_decode($this->model->condition_value);
    }
}
