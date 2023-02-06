<?php

namespace App\Services;

use App\Assets\Location;
use App\Contracts\BlockedProductsInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\IpLocatorInterface;
use App\Exceptions\AuthorizationException;
use App\Exceptions\CommonException;
use App\Exceptions\InvalidCouponException;
use App\Exceptions\InvalidGiftException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Cart\Cart;
use App\Models\User\User;
use App\Repositories\CartRepository;
use App\Repositories\Order\OrderRepository;
use Illuminate\Http\Request;

/**
 * Class CartServiceService
 * @package App\Common\Cart
 */
class CartService implements CartServiceInterface
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var CartRepository
     */
    private CartRepository $cartRepository;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var BlockedProductsInterface
     */
    private BlockedProductsInterface $blockedProducts;

    /**
     * @var IpLocatorInterface
     */
    private IpLocatorInterface $ipLocator;

    /**
     * @var User
     */
    private $user;

    /**
     * CartServiceService constructor.
     *
     * @param Request                  $request
     * @param OrderRepository          $orderRepository
     * @param CartRepository           $cartRepository
     * @param BlockedProductsInterface $blockedProducts
     * @param IpLocatorInterface       $ipLocator
     */
    public function __construct(Request $request, OrderRepository $orderRepository, CartRepository $cartRepository, BlockedProductsInterface $blockedProducts, IpLocatorInterface $ipLocator)
    {
        $this->request         = $request;
        $this->user            = $request->user();
        $this->orderRepository = $orderRepository;
        $this->cartRepository  = $this->initCartRepository($cartRepository);
        $this->blockedProducts = $blockedProducts;
        $this->ipLocator       = $ipLocator;
    }

    /**
     * @param bool $setTaxRate
     *
     * @return Cart
     * @throws ResourceNotFoundException
     */
    public function get(bool $setTaxRate = false): Cart
    {
        $cart = $this->cartRepository->get();

        if ($setTaxRate && !$cart->isEmpty()) {
            $location = empty($cart->getLocation()) ? $this->ipLocator->get($this->request->ip()) : $cart->getLocation();
            $cart     = $this->setTaxRate($location);
        }

        return $cart;

    }

    /**
     * @param int         $id
     * @param int         $quantity
     * @param string|null $section
     *
     * @return Cart
     * @throws AuthorizationException
     * @throws ResourceNotFoundException
     */
    public function addItem(int $id, int $quantity = 1, ?string $section = ''): Cart
    {
        if ($this->blockedProducts->hasProduct($id)) {
            throw new AuthorizationException('Product was already bought.');
        }

        return $this->cartRepository->addItem($id, $quantity, $section);
    }

    /**
     * @param int $id
     * @param int $quantity
     *
     * @return Cart
     */
    public function updateItem(int $id, int $quantity): Cart
    {
        return $this->cartRepository->updateItem($id, $quantity);
    }

    /**
     * @param int $id
     *
     * @return Cart
     */
    public function removeItem(int $id): Cart
    {
        return $this->cartRepository->removeItem($id);
    }

    /**
     * @param string $code
     *
     * @return Cart
     * @throws InvalidCouponException
     * @throws CommonException
     */
    public function addCoupon(string $code): Cart
    {
        return $this->cartRepository->setCoupon($code);
    }

    /**
     * @param string $code
     *
     * @return Cart
     * @throws CommonException
     * @throws InvalidGiftException
     */
    public function addGift(string $code): Cart
    {
        return $this->cartRepository->setGift($code);
    }

    /**
     * @param Location $location
     *
     * @return Cart
     * @throws ResourceNotFoundException
     */
    public function setTaxRate(Location $location): Cart
    {
        return $this->cartRepository->setTaxRate($location);
    }

    /**
     * @param array $data
     *
     * @return Cart
     */
    public function setBilling(array $data): Cart
    {
        return $this->cartRepository->setBilling($data);
    }

    /**
     * @param User|null $user
     *
     * @return CartServiceInterface
     * @throws ResourceNotFoundException
     */
    public function onUserLogin(User $user = null): CartServiceInterface
    {
        $this->user = $user;
        $this
            ->initCartRepository($this->cartRepository)
            ->copyFromStorageKey($this->getSessionKey());

        return $this;
    }

    /**
     * @return bool
     */
    public function cleanCart(): bool
    {
        return $this->cartRepository->clearCart();
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return CartServiceInterface
     */
    public function checkCartOnLogin(): CartServiceInterface
    {
        $cart = $this->cartRepository->get();

        foreach ($cart->getItems() as $item) {
            if ($this->blockedProducts->hasProduct($item->getProduct()->id)) {
                $this->removeItem($item->getProduct()->id);
            }
        }

        return $this;
    }

    /**
     * @param CartRepository $repository
     *
     * @return CartRepository
     * @throws ResourceNotFoundException
     */
    private function initCartRepository(CartRepository $repository): CartRepository
    {
        $this->cartRepository = $repository
            ->setStorageKey($this->getStorageKey())
            ->setShortTimePersistence(empty($this->user))
            ->setUser($this->user);

        if (!empty($this->user) && empty($billing = $this->get()->getBilling())) {
            $this->fillBilling();
        }

        return $this->cartRepository;
    }

    /**
     * @return CartService
     */
    private function fillBilling(): CartService
    {
        $lastUserOrder = $this->orderRepository->getUserLastOrder($this->user->id);
        $getAttribute  = function ($name) use ($lastUserOrder) {
            $orderValue = $lastUserOrder ? $lastUserOrder->billing->getAttribute($name) : null;
            $userValue  = $this->user->getAttribute($name);

            return $userValue ?: $orderValue;
        };

        $this->setBilling([
            'firstName' => $getAttribute('firstName'),
            'lastName'  => $getAttribute('lastName'),
            'email'     => $getAttribute('email')
        ]);

        return $this;
    }

    /**
     * @return string
     */
    private function getStorageKey(): string
    {
        return $this->user
            ? $this->getUserKey()
            : $this->getSessionKey();
    }

    /**
     * @param bool $create
     *
     * @return string
     */
    private function getSessionKey($create = true): string
    {
        if ($create && empty($key = $this->request->session()->get(CartServiceInterface::CART_SESSION_KEY))) {
            $key = uniqid() . microtime(true);
            $this->request->session()->put(CartServiceInterface::CART_SESSION_KEY, $key);
        }

        return $key;
    }

    /**
     * @param User|null $user
     *
     * @return string
     */
    private function getUserKey(User $user = null): string
    {
        if (empty($user)) {
            $user = $this->user;
        }

        return 'cart:user:' . $user->id;
    }
}