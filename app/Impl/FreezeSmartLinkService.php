<?php

namespace App\Impl;

use App\Interfaces\FreezeSmartLinkServiceInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;

class FreezeSmartLinkService implements FreezeSmartLinkServiceInterface
{
    public function __construct(private StatableSmartLinkRepositoryInterface $repository)
    {
    }

    public function shouldSmartLinkBeFreezed(): bool
    {
        $smartLink = $this->repository->read();
        if ($smartLink->getExpiresAt()->isPast()) {
            return true;
        }

        if (!$smartLink->hasActiveRules()) {
            return true;
        }

        return false;
    }
}
