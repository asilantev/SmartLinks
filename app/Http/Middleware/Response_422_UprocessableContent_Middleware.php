<?php

namespace App\Http\Middleware;

use App\Interfaces\FreezeSmartLinkServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Response_422_UprocessableContent_Middleware
{
    public function __construct(private FreezeSmartLinkServiceInterface $freezeSmartLinkService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->freezeSmartLinkService->shouldSmartLinkBeFreezed()) {
            return \response('', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $next($request);
    }
}
