<?php

namespace App\MailChimp\Services;

use App\Assets\MailChimp\CartAction;
use App\MailChimp\Repositories\CartRepository;
use App\MailChimp\Repositories\ProductRepository;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Exception;
use MailchimpMarketing\Api\EcommerceApi;

/**
 * Class EcommerceService
 * @package App\MailChimp\Services
 */
class EcommerceService
{
    /**
     * @var EcommerceApi
     */
    private EcommerceApi $gateway;

    /**
     * @var array
     */
    private array $config;

    /**
     * StoreRepository constructor.
     *
     * @param EcommerceApi $gateway
     * @param array        $config
     */
    public function __construct(EcommerceApi $gateway, array $config)
    {
        $this->gateway = $gateway;
        $this->config  = $config;
    }

    // BEGIN PRODUCTS INTERACTION

    /**
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function updateProduct(Product $product): bool
    {
        $productRepository = $this->productRepository();

        return $productRepository->exists($product->id)
            ? $productRepository->update($product)
            : $productRepository->store($product);
    }

    /**
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function deleteProduct(Product $product): bool
    {
        return $this->productRepository()->destroy($product);
    }
    // END PRODUCTS INTERACTION

    // BEGIN CART INTERACTION
    /**
     * @return array
     * @throws Exception
     */
    public function getCarts(): array
    {
        return $this->cartRepository()->all();
    }

    /**
     * @param CartAction $action
     *
     * @return bool
     * @throws Exception
     */
    public function updateCartItem(CartAction $action): bool
    {
        $cartRepository = $this->cartRepository();
        $userId         = $action->getUser()->id;
        if (!$cartRepository->exists($userId)) {
            return $cartRepository->store($action);
        }

        $item = $action->getCart()->getItem($action->getItemId());
        $line = $cartRepository->getLine($userId, $action->getItemId());

        return empty($line)
            ? $cartRepository->storeLine($userId, $item)
            : $cartRepository->updateLine($line->id, $userId, $item);
    }

    /**
     * @param CartAction $action
     *
     * @return bool
     * @throws Exception
     */
    public function deleteCartItem(CartAction $action): bool
    {
        $cartRepository = $this->cartRepository();
        $userId         = $action->getUser()->id;

        return $cartRepository->exists($userId)
            ? $cartRepository->destroyLine($userId, $action->getItemId())
            : true;
    }

    /**
     * @param Order $order
     *
     * @return bool
     * @throws Exception
     */
    public function deleteCart(Order $order): bool
    {
        return $this->cartRepository()->destroy($order->userId);
    }
    // END CART INTERACTION

    /**
     * @return ProductRepository
     */
    public function productRepository(): ProductRepository
    {
        return new ProductRepository($this->gateway, $this->config['storeId']);
    }

    /**
     * @return CartRepository
     */
    public function cartRepository(): CartRepository
    {
        return new CartRepository($this->gateway, $this->config['storeId']);
    }
}
