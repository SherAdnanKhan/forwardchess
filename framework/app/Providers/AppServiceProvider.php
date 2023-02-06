<?php

namespace App\Providers;

use App\Common\EcommerceService;
use App\Contracts\EcommerceInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isProduction()) {
            URL::forceScheme('https');
        }

//        Queue::failing(function (JobFailed $event) {
//            // send email to me about this error
//            // $event->connectionName
//            // $event->job
//            // $event->exception
//        });

        $this->app->bind('path.public', function () {
            return realpath(base_path() . '/../');
        });

        Relation::morphMap([
            'product' => 'App\Models\Product\Product',
            'gift'    => 'App\Models\Gift',
        ]);

        $this->app->singleton(EcommerceInterface::class, EcommerceService::class);

        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
