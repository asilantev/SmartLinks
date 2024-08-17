<?php

namespace App\Http\Middleware;

use App\Interfaces\SmartLinkRedirectServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Response_307_TemporaryRedirect_Middleware
{
    public function __construct(private SmartLinkRedirectServiceInterface $smartLinkRedirectService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $targetUrl = $this->smartLinkRedirectService->evaluate();

        if ($targetUrl) {
            return redirect($targetUrl);
        }

        return $next($request);
    }
}
