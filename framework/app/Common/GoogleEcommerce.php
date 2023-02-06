<?php

namespace App\Common;

use App\Models\Order\Item;
use App\Models\Order\Order;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class GoogleEcommerce
 * @package App\Common
 */
class GoogleEcommerce implements Arrayable
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var array
     */
    private $transaction = [];

    /**
     * @var array
     */
    private $items = [];

    /**
     * @param Order $order
     *
     * @return GoogleEcommerce
     */
    public static function make(Order $order): GoogleEcommerce
    {
        $instance = new static;
        $instance
            ->setOrder($order)
            ->init();

        return $instance;
    }

    /**
     * @param Order $order
     *
     * @return GoogleEcommerce
     */
    public function setOrder(Order $order): GoogleEcommerce
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'ecommerce' => [
                'purchase' => [
                    'actionField' => $this->transaction,
                    'products'    => $this->items
                ]
            ]];
    }

    /**
     * @return GoogleEcommerce
     */
    private function init(): self
    {
        $this->transaction = [
            'id'       => $this->order->refNo,
            'revenue'  => $this->order->total,
            'shipping' => 0,
            'tax'      => $this->order->taxAmount,
            'currency' => $this->order->currency,
            'coupon'   => empty($this->order->coupon) ? '' : $this->order->coupon
        ];

        foreach ($this->order->items as $orderItem) {
            $item = [
                'name'     => $orderItem->name,
                'price'    => $orderItem->unitPrice,
                'quantity' => 1
            ];

            if ($orderItem->type === Item::TYPE_PRODUCT) {
                $categories = $orderItem->product->categories->map(function ($category) {
                    return $category->name;
                })->implode(', ');

                $item['id']       = $orderItem->product->sku;
                $item['category'] = $categories;
            } else {
                $item['id']       = $orderItem->gift->code;
                $item['category'] = 'Gift cards';
            }

            $this->items[] = $item;
        }

        return $this;
    }
}