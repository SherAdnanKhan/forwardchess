<?php

namespace App\Contracts;

use App\Models\Order\Order;
use App\Models\User\User;

/**
 * Interface BlockedProductsInterface
 * @package App\Contracts
 */
interface BlockedProductsInterface
{
    /**
     * @return array
     */
    public function getProducts(): array;

    /**
     * @param int $productId
     *
     * @return bool
     */
    public function hasProduct(int $productId): bool;

    /**
     * @param array $ids
     *
     * @return mixed
     */
    public function store(array $ids);

    /**
     * @return mixed
     */
    public function clear();

    /**
     * @param User $user
     *
     * @return BlockedProductsInterface
     */
    public function onAuthenticationChanged(User $user): BlockedProductsInterface;

    /**
     * @param Order $order
     *
     * @return BlockedProductsInterface
     */
    public function onOrderPlaced(Order $order): BlockedProductsInterface;

    /**
     * @param array $mobileOrders
     *
     * @return BlockedProductsInterface
     */
    public function blockMobileProducts(array $mobileOrders): BlockedProductsInterface;
}