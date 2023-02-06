<?php

namespace App\Contracts;

use App\Assets\Location;
use App\Models\Cart\Cart;
use App\Models\User\User;

/**
 * Interface CartServiceInterface
 * @package App\Contracts
 */
interface CartServiceInterface
{
    const CART_SESSION_KEY       = 'cart';
    const LAST_ORDER_SESSION_KEY = 'lastOrderId';
    const GIFT_CARD_KEY          = 'giftCard';

    /**
     * @param bool $setTaxRate
     *
     * @return Cart
     */
    public function get(bool $setTaxRate = false): Cart;

    /**
     * @param int $id
     * @param int $quantity
     * @param string $section
     *
     * @return Cart
     */
    public function addItem(int $id, int $quantity = 1, string $section = ''): Cart;

    /**
     * @param int $id
     * @param int $quantity
     *
     * @return Cart
     */
    public function updateItem(int $id, int $quantity): Cart;

    /**
     * @param int $id
     *
     * @return Cart
     */
    public function removeItem(int $id): Cart;

    /**
     * @param string $code
     *
     * @return Cart
     */
    public function addCoupon(string $code): Cart;

    /**
     * @param string $code
     *
     * @return Cart
     */
    public function addGift(string $code): Cart;

    /**
     * @param Location $location
     *
     * @return Cart
     */
    public function setTaxRate(Location $location): Cart;

    /**
     * @param array $data
     *
     * @return Cart
     */
    public function setBilling(array $data): Cart;

    /**
     * @param User $user
     *
     * @return CartServiceInterface
     */
    public function onUserLogin(User $user): CartServiceInterface;

    /**
     * @return bool
     */
    public function cleanCart(): bool;

    /**
     * @return User
     */
    public function getUser(): ?User;

    /**
     * @return CartServiceInterface
     */
    public function checkCartOnLogin(): CartServiceInterface;
}