<?php

namespace App\Http\Middleware;

use App\Interfaces\StatableSmartLinkRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Response_404_NotFound_Middleware
{
    public function __construct(private StatableSmartLinkRepositoryInterface $repository)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $statableSmartLink = $this->repository->read();
        if ($statableSmartLink == null || !$statableSmartLink->hasActiveRules()) {
            return \response('', Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
