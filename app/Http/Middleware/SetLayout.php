<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLayout
{
    public function handle(Request $request, Closure $next, string $layout): Response
    {
        config(['livewire.layout' => 'layouts::' . $layout]);
        config(['livewire.component_layout' => 'layouts::' . $layout]);

        return $next($request);
    }
}
