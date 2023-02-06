<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class HttpsProtocol
 * @package App\Http\Middleware
 */
class HttpsProtocol
{
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && isProduction()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}