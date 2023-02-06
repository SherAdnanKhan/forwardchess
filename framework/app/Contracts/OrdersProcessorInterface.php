<?php

namespace App\Contracts;

use App\Models\Order\Order;

/**
 * Interface OrdersProcessorInterface
 * @package App\Contracts
 */
interface OrdersProcessorInterface
{
    /**
     * @return void
     */
    public function processPendingOrders();

    /**
     * @param Order $order
     *
     * @return mixed
     */
    public function sendGifts(Order $order);
}