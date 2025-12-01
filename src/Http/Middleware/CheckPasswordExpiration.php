<?php

namespace Wave8\Factotum\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wave8\Factotum\Base\Http\Responses\Api\ApiResponse;

class CheckPasswordExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->isCurrentPasswordExpired()) {
            return ApiResponse::unauthorized(__('passwords.expired'));
        }

        return $next($request);
    }
}
