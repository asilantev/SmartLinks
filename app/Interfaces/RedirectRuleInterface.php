<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface RedirectRuleInterface
{
    public function getTargetUrl(): string;
    public function getConditions(): Collection;
}
