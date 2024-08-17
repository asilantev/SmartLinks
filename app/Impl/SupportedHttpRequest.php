<?php

namespace App\Impl;

use App\Interfaces\SupportedHttpRequestInterface;
use Illuminate\Http\Request;

class SupportedHttpRequest implements SupportedHttpRequestInterface
{
    private array $allowedHttpMethods = [Request::METHOD_GET];
    public function __construct(private Request $request)
    {
    }

    public function methodIsSupported(): bool
    {
        return in_array($this->request->getMethod(), $this->allowedHttpMethods);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getPlatform(): string
    {
        return trim($this->request->header('sec-ch-ua-platform'), '"');
    }

    public function getBrowser(): string
    {
        return trim($this->request->header('sec-ch-ua'), '"');
    }
}
