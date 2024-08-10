<?php

namespace App\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Interfaces\CommandInterface;

class TimeIntervalConditionCommand implements CommandInterface
{

    public function __construct(private \stdClass $params)
    {
    }

    /**
     * @throws ConditionRuleHandlerProcessException
     */
    public function execute(): void
    {
        $now = new \DateTime();
        $start = new \DateTime($this->params->start->date);
        $end = new \DateTime($this->params->end->date);

        if (!($start < $now && $now < $end)) {
            throw new ConditionRuleHandlerProcessException();
        }
    }
}
