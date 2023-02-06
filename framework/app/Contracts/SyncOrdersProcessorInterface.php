<?php

namespace App\Contracts;

/**
 * Interface OrdersProcessorInterface
 * @package App\Contracts
 */
interface SyncOrdersProcessorInterface
{
    /**
     * @return void
     */
    public function syncOrders();
}
