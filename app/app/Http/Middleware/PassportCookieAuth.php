<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PassportCookieAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->headers->has('Authorization')
            && $request->hasCookie('access_token')) {

            $request->headers->set(
                'Authorization',
                'Bearer ' . $request->cookie('access_token')
            );
        }

        return $next($request);
    }
}
