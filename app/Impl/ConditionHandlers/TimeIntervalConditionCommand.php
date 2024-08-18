<?php

namespace App\Impl\ConditionHandlers;

use App\Exceptions\ConditionRuleHandlerProcessException;
use App\Interfaces\CommandInterface;
use Illuminate\Support\Carbon;

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
        $now = Carbon::now();
        $start = Carbon::parse($this->params->start);
        $end = Carbon::parse($this->params->end);

        if (!($start < $now && $now < $end)) {
            throw new ConditionRuleHandlerProcessException();
        }
    }
}
