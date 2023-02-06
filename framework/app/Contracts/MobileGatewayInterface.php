<?php

namespace App\Contracts;

use App\Models\Order\Order;
use App\Models\User\User;

/**
 * Interface MobileGatewayInterface
 * @package App\Contracts
 */
interface MobileGatewayInterface
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function registerAccount(User $user): bool;

    /**
     * @param User $user
     *
     * @return bool
     */
    public function updateAccount(User $user): bool;

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function registerWebPurchase(Order $order): bool;

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function sendMail(array $options);

    /**
     * @param array $email
     *
     * @return mixed
     */
    public function getMobilePurchase(string $email);

    /**
     * @param array $skuList
     *
     * @return mixed
     */
    public function getRecommendedBooks(array $skuList): array;

    /**
     * @param string $productSku
     *
     * @return mixed|array
     */
    public function getReviews(string $productSku): array;

    /**
     * @param array $fields
     *
     *
     * @return mixed|array
     */
    public function postReview(array $fields): array;

    /**
     * @param string $email
     * @return bool
     */
    public function getFirebaseToken(string $email): bool;
}
