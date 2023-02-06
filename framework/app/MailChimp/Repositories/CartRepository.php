<?php

namespace App\MailChimp\Repositories;

use App\Assets\MailChimp\CartAction;
use App\Models\Cart\Item;
use Exception;
use MailchimpMarketing\Api\EcommerceApi;
use stdClass;

/**
 * Class CartRepository
 * @package App\MailChimp\Repositories
 */
class CartRepository
{
    use ApiCall;

    /**
     * @var EcommerceApi
     */
    private EcommerceApi $gateway;

    /**
     * @var string
     */
    private string $storeId;

    /**
     * ProductRepository constructor.
     *
     * @param EcommerceApi $gateway
     * @param string       $storeId
     */
    public function __construct(EcommerceApi $gateway, string $storeId)
    {
        $this->gateway = $gateway;
        $this->storeId = $storeId;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function all(): array
    {
//        $this->gateway->deleteStoreCart(self::MAIN_STORE_ID, 'cart:3025');

        $response = $this->makeCall(function () {
            return $this->gateway->getStoreCarts($this->storeId);
        });

        $response->handle();

        return $response->getResponse()->carts;
    }

    /**
     * @param int $userId
     *
     * @return mixed|null
     * @throws Exception
     */
    public function get(int $userId): ?stdClass
    {
        $response = $this->makeCall(function () use ($userId) {
            return $this->gateway->getStoreCart($this->storeId, $this->makeCartId($userId));
        });

        if (!$response->isSuccess() && ($response->getErrorStatus() !== 404)) {
            $response->handle();
        }

        return $response->isSuccess() ? $response->getResponse() : null;
    }

    /**
     * @param int $userId
     *
     * @return bool
     * @throws Exception
     */
    public function exists(int $userId): bool
    {
        $cart = $this->get((string)$userId);

        return !is_null($cart);
    }

    /**
     * @param CartAction $action
     *
     * @return bool
     * @throws Exception
     */
    public function store(CartAction $action): bool
    {
        $cart   = $action->getCart();
        $user   = $action->getUser();
        $cartId = $this->makeCartId($user->id);

        $lines = [];
        foreach ($cart->getItems() as $item) {
            $lines[] = $this->makeCartItem($item);
        }

        $data = [
            'id'            => $cartId,
            'currency_code' => 'USD',
            'order_total'   => toFloatAmount($cart->getTotal()),
            'checkout_url'  => env('APP_URL') . '/shopping-cart',
            'lines'         => $lines,

            'customer' => [
                'id'            => (string)$user->id,
                'email_address' => $user->email,
                'opt_in_status' => true,
                'first_name'    => $user->firstName ?: '',
                'last_name'     => $user->lastName ?: ''
            ]
        ];

        $response = $this->makeCall(function () use ($data) {
            return $this->gateway->addStoreCart($this->storeId, $data);
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param int $userId
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(int $userId): bool
    {
        if (!$this->exists($userId)) {
            return true;
        }

        $response = $this->makeCall(function () use ($userId) {
            $this->gateway->deleteStoreCart($this->storeId, $this->makeCartId($userId));
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param int $userId
     *
     * @return array
     * @throws Exception
     */
    public function allLines(int $userId): array
    {
        $cart = $this->get($userId);

        return empty($cart) ? [] : $cart->lines;
    }

    /**
     * @param int $userId
     * @param int $itemId
     *
     * @return stdClass|null
     * @throws Exception
     */
    public function getLine(int $userId, int $itemId): ?stdClass
    {
        $lines = $this->allLines($userId);
        $line  = $this->findLine($lines, $itemId);

        return empty($line) ? null : $line;
    }

    /**
     * @param int  $userId
     * @param Item $item
     *
     * @return bool
     * @throws Exception
     */
    public function storeLine(int $userId, Item $item): bool
    {
        $response = $this->makeCall(function () use ($userId, $item) {
            $cartId = $this->makeCartId($userId);
            return $this->gateway->addCartLineItem($this->storeId, $cartId, $this->makeCartItem($item));
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param string $lineId
     * @param int    $userId
     * @param Item   $item
     *
     * @return bool
     * @throws Exception
     */
    public function updateLine(string $lineId, int $userId, Item $item): bool
    {
        $response = $this->makeCall(function () use ($lineId, $userId, $item) {
            $cartId = $this->makeCartId($userId);
            return $this->gateway->updateCartLineItem($this->storeId, $cartId, $lineId, $this->makeCartItem($item));
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param int $userId
     * @param int $itemId
     *
     * @return bool
     * @throws Exception
     */
    public function destroyLine(int $userId, int $itemId): bool
    {
        $lines = $this->allLines($userId);
        $line  = $this->findLine($lines, $itemId);
        if (empty($line)) {
            return true;
        }

        if (count($lines) <= 1) {
            return $this->destroy($userId);
        }

        $response = $this->makeCall(function () use ($line, $userId) {
            $cartId = $this->makeCartId($userId);
            $this->gateway->deleteCartLineItem($this->storeId, $cartId, $line->id);
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param int $userId
     *
     * @return string
     */
    private function makeCartId(int $userId): string
    {
        return "cart:{$userId}";
    }

    /**
     * @param int $itemId
     *
     * @return string
     */
    private function makeLineId(int $itemId): string
    {
        return "item:{$itemId}";
    }

    /**
     * @param Item $item
     *
     * @return array
     * @throws Exception
     */
    private function makeCartItem(Item $item): array
    {
        $productId = (string)$item->getProduct()->id;

        return [
            'id'                 => $this->makeLineId($productId),
            'product_id'         => $productId,
            'product_variant_id' => $productId,
            'quantity'           => $item->getQuantity(),
            'price'              => toFloatAmount($item->getTotal())
        ];
    }

    /**
     * @param array $lines
     * @param int   $itemId
     *
     * @return stdClass|null
     */
    private function findLine(array $lines, int $itemId): ?stdClass
    {
        if (empty($lines)) {
            return null;
        }

        $lineId = $this->makeLineId($itemId);
        $line   = array_first($lines, function ($item) use ($lineId) {
            return $item->id === $lineId;
        });

        return empty($line) ? null : $line;
    }
}