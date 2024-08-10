<?php

namespace App\Http\Middleware;

use App\Interfaces\SupportedHttpRequestInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Response_405_MethodNotAllowed_Middleware
{
    public function __construct(private SupportedHttpRequestInterface $supportedHttpRequest)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->supportedHttpRequest->methodIsSupported()) {
            return \response('', Response::HTTP_METHOD_NOT_ALLOWED);
        }


        return $next($request);
    }
}
