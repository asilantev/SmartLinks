<?php

namespace App\Interfaces;

interface RuleConditionInterface
{
    public function getType(): ConditionTypeInterface;
    public function getValue(): ?\stdClass;
}
