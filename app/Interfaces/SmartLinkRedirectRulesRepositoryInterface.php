<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface SmartLinkRedirectRulesRepositoryInterface
{
    public function read(): Collection;
}
