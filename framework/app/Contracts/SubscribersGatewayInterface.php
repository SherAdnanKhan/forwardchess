<?php

namespace App\Contracts;

use App\Models\Newsletter;

/**
 * Interface MailChimpGatewayInterface
 * @package App\Contracts
 */
interface SubscribersGatewayInterface
{
    public function subscribe(Newsletter $newsletter): SubscribersGatewayInterface;

    public function unsubscribe(Newsletter $newsletter): SubscribersGatewayInterface;
}