<?php

namespace App\Interfaces;

interface StatableSmartLinkRepositoryInterface
{
    public function read(): ?StatableSmartLinkInterface;
}
