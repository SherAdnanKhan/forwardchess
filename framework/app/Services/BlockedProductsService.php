<?php

namespace App\Services;

use App\Contracts\BlockedProductsInterface;
use App\Models\Order\Order;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Class BlockedProductsService
 * @package App\Services
 */
class BlockedProductsService implements BlockedProductsInterface
{
    const PRODUCTS_BOUGHT_KEYS = 'boughtProducts';

    /**
     * @var Guard
     */
    protected Guard $auth;

    /**
     * @return array
     */
    public function getProducts(): array
    {
        $ids = Session::get(self::PRODUCTS_BOUGHT_KEYS);

        return empty($ids) ? [] : $ids;
    }

    /**
     * @param int $productId
     *
     * @return bool
     */
    public function hasProduct(int $productId): bool
    {
        return in_array($productId, $this->getProducts());
    }

    /**
     * @param array $ids
     *
     * @return BlockedProductsInterface
     */
    public function store(array $ids): BlockedProductsInterface
    {
        if (!empty($previous = $this->getProducts())) {
            Session::put(self::PRODUCTS_BOUGHT_KEYS, array_merge($previous, $ids));
        } else {
            Session::put(self::PRODUCTS_BOUGHT_KEYS, $ids);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clear(): BlockedProductsInterface
    {
        Session::remove(self::PRODUCTS_BOUGHT_KEYS);

        return $this;
    }

    /**
     * @param User $user
     *
     * @return BlockedProductsInterface
     */
    public function onAuthenticationChanged(User $user): BlockedProductsInterface
    {
        $user ? $this->store($this->retrieveProducts($user->id)) : $this->clear();

        return $this;
    }

    /**
     * @param array $mobileOrders
     *
     * @return BlockedProductsInterface
     */
    public function blockMobileProducts(array $mobileOrders = []): BlockedProductsInterface
    {
        if (count($mobileOrders) > 0) {
            $ids        = array_column($mobileOrders, 'productId');
            $productIds = DB::table('products')
                ->select('id')
                ->whereIn('sku', $ids)
                ->get()
                ->map(function ($item) {
                    return $item->id;
                })->toArray();

            $this->store($productIds);

            return $this;
        }

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return BlockedProductsInterface
     */
    public function onOrderPlaced(Order $order): BlockedProductsInterface
    {
        $productIds = $this->getProducts();

        foreach ($order->items as $item) {
            if ($item->product) {
                $productIds[] = $item->product->id;
            }
        }

        $this->store($productIds);

        return $this;
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    private function retrieveProducts(int $userId): array
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.orderId')
            ->select('productId')
            ->where('orders.userId', '=', $userId)
            ->where('orders.status', '=', Order::STATUS_COMPLETED)
            ->get()
            ->map(function ($item) {
                return $item->productId;
            })->toArray();
    }
}