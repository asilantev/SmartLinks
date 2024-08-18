<?php

namespace App\Impl;

use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\StatableSmartLinkInterface;
use App\Interfaces\StatableSmartLinkRepositoryInterface;

class StatableSmartLinkRepository implements StatableSmartLinkRepositoryInterface
{
    public function __construct(private DbRepositoryInterface $dbRepository)
    {
    }

    public function read(): ?StatableSmartLinkInterface
    {
       $model = $this->dbRepository->read();
       return $model != null ? new StatableSmartLink($model) : null;
    }
}
