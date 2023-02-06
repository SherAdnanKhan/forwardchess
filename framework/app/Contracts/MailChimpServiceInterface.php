<?php

namespace App\Contracts;

use App\Assets\MailChimp\CartAction;
use App\Assets\MailChimp\ProductAction;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\User\User;

/**
 * Interface MailChimpServiceInterface
 * @package App\Contracts
 */
interface MailChimpServiceInterface
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function registerUser(User $user): bool;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function subscribe(User $user): bool;

    /**
     * @param User $user
     * @param bool $hardDelete
     *
     * @return bool
     */
    public function unsubscribe(User $user, bool $hardDelete = false): bool;

    /**
     * @param ProductAction $action
     *
     * @return bool
     */
    public function syncProduct(ProductAction $action): bool;

    /**
     * @param CartAction $action
     *
     * @return bool
     */
    public function syncCartItem(CartAction $action): bool;

    /**
     * @param User    $user
     * @param Product $product
     *
     * @return bool
     */
    public function productAbandoned(User $user, Product $product): bool;

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function clearUserAutomations(Order $order): bool;
}
