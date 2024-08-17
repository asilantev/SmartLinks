<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface SupportedHttpRequestInterface
{
    public function methodIsSupported(): bool;
    public function getRequest(): Request;
    public function getPlatform(): string;
    public function getBrowser(): string;
}
