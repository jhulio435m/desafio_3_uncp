<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!str_contains($request->getHost(), 'localhost') && !str_contains($request->getHost(), '127.0.0.1')) {
            URL::forceScheme('https');
        }
        return $next($request);
    }
}
