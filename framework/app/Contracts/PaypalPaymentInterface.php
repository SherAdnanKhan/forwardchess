<?php

namespace App\Contracts;

use App\Models\Order\Order;
use PayPal\Api\Payment;
use PayPal\Rest\ApiContext;

interface PaypalPaymentInterface
{
    /**
     * @param Order $order
     *
     * @return PaypalPaymentInterface
     */
    public function setOrder(Order $order): PaypalPaymentInterface;

    /**
     * @param ApiContext $context
     *
     * @return PaypalPaymentInterface
     */
    public function createPayment(ApiContext $context): PaypalPaymentInterface;

    /**
     * @param string $id
     *
     * @return PaypalPaymentInterface
     */
    public function setProfileID(string $id): PaypalPaymentInterface;

    /**
     * @return null|string
     */
    public function getRedirectUrl(): ?string;

    /**
     * @return string
     */
    public function getPaymentID(): string;

    /**
     * @param ApiContext $context
     * @param string     $id
     * @param string     $payerId
     *
     * @return Payment
     */
    public function getPaymentDetails(ApiContext $context, string $id, string $payerId): Payment;

    /**
     * @param Payment $payment
     *
     * @return array
     */
    public function getOrderPaymentDetails(Payment $payment): array;
}