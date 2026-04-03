<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenAt
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('web')->check()) {
            auth('web')->user()->forceFill([
                'last_seen_at' => now(),
            ])->save();
        }

        return $next($request);
    }
}
