<?php

namespace App\Impl;

use App\Interfaces\DbRepositoryInterface;
use App\Interfaces\SmartLinkInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class DbRepository implements DbRepositoryInterface
{
    public function __construct(private Collection $collection, private SmartLinkInterface $smartLink)
    {
    }

    public function read()
    {
        return $this->collection->filter(function ($model) {
            return Arr::get($model, 'slug') === $this->smartLink->getValue();
        })->first();
    }
}
