<?php

namespace App\Impl;

use App\Interfaces\RedirectRuleInterface;
use App\Interfaces\RuleConditionInterface;
use Illuminate\Support\Collection;

class RedirectRule implements RedirectRuleInterface
{
    public function __construct(private \App\Models\RedirectRule $model)
    {
    }


    public function getTargetUrl(): string
    {
        return $this->model->target_url;
    }

    public function getConditions(): Collection
    {
        return $this->model->conditions()->get()->map(fn($item) => app(RuleConditionInterface::class, [$item]));
    }
}
