<?php

namespace App\Impl;

use App\Interfaces\ConditionTypeInterface;

class ConditionType implements ConditionTypeInterface
{

    public function __construct(private \App\Models\ConditionType $model)
    {
    }

    public function getCode(): string
    {
        return (string)$this->model->code;
    }
}
