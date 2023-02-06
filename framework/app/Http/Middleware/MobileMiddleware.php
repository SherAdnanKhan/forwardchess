<?php
namespace App\Http\Middleware;

use App\Exceptions\AuthorizationException;
use Closure;

class MobileMiddleware
{
    /**
     * @param         $request
     * @param Closure $next
     *
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('x-authorization') !== env('MOBILE_SECRET')) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}