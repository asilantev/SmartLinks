<?php

namespace App\Impl;

use App\Interfaces\StatableSmartLinkInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class StatableSmartLink implements StatableSmartLinkInterface
{
    public function __construct(private \App\Models\SmartLink $model)
    {
    }

    public function getSlug(): string
    {
        return $this->model->slug;
    }

    public function hasActiveRules(): bool
    {
        return (bool)$this->model->redirectRules()->firstWhere('is_active', '=', 1);
    }

    public function getExpiresAt(): ?CarbonInterface
    {
        $expiresAt = $this->model->expires_at;
        return $expiresAt ? Carbon::parse($expiresAt) : null;
    }
}
