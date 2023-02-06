<?php

namespace App\Providers;

use App\Common\Mail\ApiEmailTransport;
use App\Contracts\MobileGatewayInterface;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    public function boot()
    {
        app('mail.manager')->extend('api', function () {
            return new ApiEmailTransport(app(MobileGatewayInterface::class));
        });
    }
}
