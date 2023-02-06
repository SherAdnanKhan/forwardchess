<?php

namespace App\Http\Controllers\Api\Cart;

use App\Contracts\CartServiceInterface;
use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Http\Resources\Order\OrderResource;
use App\Models\Cart\Cart;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

/**
 * Class CartController
 * @package App\Http\Controllers\Cart
 */
class CartController extends Controller
{
    /**
     * @param CartServiceInterface $cartService
     *
     * @return CartResource
     */
    public function index(CartServiceInterface $cartService): CartResource
    {
        return $this->cartResponse($cartService->get());
    }

    /**
     * @param Request              $request
     * @param CartServiceInterface $cartService
     *
     * @return CartResource
     * @throws CommonException
     */
    public function addItem(Request $request, CartServiceInterface $cartService): CartResource
    {
        $id       = $request->get('productId', null);
        $section  = $request->get('section', null);
        $quantity = (int)$request->get('quantity', 1);

        if (empty($id)) {
            throw new CommonException('Invalid product id!');
        }

        return $this->cartResponse($cartService->addItem($id, $quantity, $section));
    }

    /**
     * @param Request              $request
     * @param CartServiceInterface $cartService
     * @param                      $productId
     *
     * @return CartResource
     */
    public function updateItem(Request $request, CartServiceInterface $cartService, $productId): CartResource
    {
        $quantity = (int)$request->get('quantity', 1);

        $cartService = $cartService->updateItem($productId, $quantity);

        return $this->cartResponse($cartService);
    }

    /**
     * @param CartServiceInterface $cartService
     * @param                      $productId
     *
     * @return CartResource
     */
    public function removeItem(CartServiceInterface $cartService, $productId): CartResource
    {
        $cartService = $cartService->removeItem($productId);

        return $this->cartResponse($cartService);
    }

    /**
     * @param CartServiceInterface $cartService
     * @param string               $code
     *
     * @return CartResource
     */
    public function addCoupon(CartServiceInterface $cartService, string $code): CartResource
    {
        return $this->cartResponse($cartService->addCoupon($code));
    }

    /**
     * @param CartServiceInterface $cartService
     * @param string               $code
     *
     * @return CartResource
     */
    public function addGift(CartServiceInterface $cartService, string $code): CartResource
    {
        return $this->cartResponse($cartService->addGift($code));
    }

    /**
     * @param CartServiceInterface $cartService
     * @param string               $countryCode
     *
     * @return CartResource
     */
    public function addTaxes(CartServiceInterface $cartService, string $countryCode): CartResource
    {
        return $this->cartResponse($cartService->setTaxRate($countryCode));
    }

    /**
     * @param Cart $cart
     *
     * @return CartResource
     */
    private function cartResponse(Cart $cart): CartResource
    {
        return CartResource::make($cart);
    }
}