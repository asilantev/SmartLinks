<?php

namespace App\Interfaces;

use Carbon\CarbonInterface;

interface StatableSmartLinkInterface
{
    public function getSlug(): string;
    public function getExpiresAt(): ?CarbonInterface;
    public function hasActiveRules(): bool;
}
