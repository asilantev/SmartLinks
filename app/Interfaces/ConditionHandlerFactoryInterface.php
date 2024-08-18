<?php

namespace App\Interfaces;

interface ConditionHandlerFactoryInterface
{
    public function create(ConditionTypeInterface $conditionType, mixed $params = null): CommandInterface;
}
