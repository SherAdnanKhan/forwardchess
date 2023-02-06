<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param         $request
     * @param Closure $next
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function handle($request, Closure $next)
    {
        $whiteList = [
            '81.196.104.28',
            '84.232.191.90',
            '103.85.8.145',
            '86.121.163.128',
        ];

        if (($this->app->isDownForMaintenance() || env('MAINTENANCE')) && !in_array($request->ip(), $whiteList)) {
            throw new MaintenanceModeException(Carbon::now()->timestamp, Carbon::now()->addHour()->timestamp, 'We are updating our website. <br> Be right back!');
        }

        return $next($request);
    }
}