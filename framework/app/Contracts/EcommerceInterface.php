<?php

namespace App\Contracts;

use App\Models\Cart\Cart;
use App\Models\Order\Order;

/**
 * Interface EcommerceInterface
 * @package App\Contracts
 */
interface EcommerceInterface
{
    public function setListName(string $listName): self;

    public function addProducts(array $products): self;

    public function addCheckout(Cart $cart): self;

    public function addPurchase(Order $order): self;

    public function getData(): ?string;
}