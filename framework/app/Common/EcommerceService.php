<?php

namespace App\Common;

use App\Assets\Ecommerce\ProductAsset;
use App\Contracts\EcommerceInterface;
use App\Models\Cart\Cart;
use App\Models\Cart\Item as CartItem;
use App\Models\Order\Item as OrderItem;
use App\Models\Order\Order;

/**
 * Class EcommerceService
 * @package App\Common
 */
class EcommerceService implements EcommerceInterface
{
    const VIEW_PRODUCTS = 'VIEW_PRODUCTS';
    const VIEW_CHECKOUT = 'VIEW_CHECKOUT';
    const PURCHASE      = 'PURCHASE';

    /**
     * @var string
     */
    private $event;

    /**
     * @var string
     */
    private $listName;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $listName
     *
     * @return EcommerceInterface
     */
    public function setListName(string $listName): EcommerceInterface
    {
        $this->listName = $listName;

        return $this;
    }

    /**
     * @param array $products
     *
     * @return EcommerceInterface
     */
    public function addProducts(array $products): EcommerceInterface
    {
        $this->event = self::VIEW_PRODUCTS;
        $this->data  = array_map(function ($product) {
            return ProductAsset::make($product)->toArray();
        }, $products);

        return $this;
    }

    /**
     * @param Cart $cart
     *
     * @return EcommerceInterface
     */
    public function addCheckout(Cart $cart): EcommerceInterface
    {
        $this->event = self::VIEW_CHECKOUT;
        $this->data  = array_values(array_map(function (CartItem $item) {
            return ProductAsset::make($item->getProduct())
                ->setQuantity($item->getQuantity())
                ->setPrice(toFloatAmount($item->getTotal()))
                ->setSection($item->getSection())
                ->toArray();
        }, $cart->getItems()));

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return EcommerceInterface
     */
    public function addPurchase(Order $order): EcommerceInterface
    {
        $this->event = self::PURCHASE;
        $products    = [];

        foreach ($order->items as $orderItem) {
            $item = [
                'id'               => ($orderItem->type === OrderItem::TYPE_PRODUCT) ? $orderItem->product->sku : $orderItem->gift->code,
                'name'             => $orderItem->name,
                'price'            => $orderItem->unitPrice,
                'quantity'         => 1,
                'purchasedSection' => $orderItem->purchasedSection,
            ];

            $products[] = $item;
        }

        foreach ($order->vouchers as $orderVoucher) {
            $products[] = [
                'id'       => $orderVoucher->id,
                'name'     => 'Voucher',
                'price'    => -1 * $orderVoucher->amount,
                'brand'    => 'Vouchers',
                'quantity' => 1
            ];
        }

        $this->data = [
            'purchase' => [
                'id'       => $order->refNo,
                'revenue'  => $order->total,
                'tax'      => $order->taxAmount,
                'shipping' => '0',
                'currency' => $order->currency,
                'coupon'   => empty($order->coupon) ? '' : $order->coupon
            ],
            'products' => $products

        ];

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): ?string
    {
        return empty($this->data)
            ? null
            : json_encode([
                'event' => $this->event,
                'data'  => $this->data
            ]);
    }
}