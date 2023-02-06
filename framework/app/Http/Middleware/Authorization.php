<?php

namespace App\Http\Middleware;

use Closure;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->root() !== env('APP_URL')) {
            return redirect(rtrim(env('APP_URL') . '/' . ltrim($request->path(), '/'), '/'));
        }

        if (!empty($authorization = $request->header('x-authorization'))) {
            $request->headers->set('authorization', $authorization);
        }

        return $next($request);
    }
}