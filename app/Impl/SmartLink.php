<?php

namespace App\Impl;

use App\Interfaces\SmartLinkInterface;
use App\Interfaces\SupportedHttpRequestInterface;

class SmartLink implements SmartLinkInterface
{
    public function __construct(private SupportedHttpRequestInterface $request)
    {
    }

    public function getValue(): string
    {
        return (string)$this->request->getRequest()->route('slug');
    }
}
