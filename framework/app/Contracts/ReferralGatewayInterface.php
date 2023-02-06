<?php

namespace App\Contracts;

use App\Models\Order\Order;

/**
 * Interface ReferralGatewayInterface
 * @package App\Contracts
 */
interface ReferralGatewayInterface
{
    /**
     * @param Order $order
     * @param array $data
     *
     * @return bool
     */
    public function purchase(Order $order, array $data = []): bool;
}