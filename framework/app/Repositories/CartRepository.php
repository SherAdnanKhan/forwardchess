<?php

namespace App\Repositories;

use App\Assets\Location;
use App\Assets\SimpleResponse;
use App\Common\Cache\CacheModel;
use App\Common\CrudActions;
use App\Contracts\BlockedProductsInterface;
use App\Contracts\CacheStorageInterface;
use App\Contracts\CountriesServiceInterface;
use App\Events\CartItemChangedEvent;
use App\Exceptions\CommonException;
use App\Exceptions\InvalidCouponException;
use App\Exceptions\InvalidGiftException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Cart\Billing;
use App\Models\Cart\Cart;
use App\Models\Cart\Item;
use App\Models\Coupon;
use App\Models\Gift;
use App\Models\Product\Product;
use App\Models\TaxRate;
use App\Models\User\User;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Carbon\Carbon;

/**
 * Class CartRepository
 * @package App\Common\Cart
 */
class CartRepository
{
    /**
     * @var CacheStorageInterface
     */
    private CacheStorageInterface $cache;

    /**
     * @var CountriesServiceInterface
     */
    private CountriesServiceInterface $countriesService;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var CouponRepository
     */
    private CouponRepository $couponRepository;

    /**
     * @var GiftRepository
     */
    private GiftRepository $giftRepository;

    /**
     * @var TaxRateRepository
     */
    private TaxRateRepository $taxRateRepository;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var BlockedProductsInterface
     */
    private BlockedProductsInterface $blockedProducts;

    /**
     * @var string
     */
    private string $storageKey;

    /**
     * @var bool
     */
    private bool $shortTimePersistence;

    /**
     * @var User|null
     */
    private ?User $user = null;

    /**
     * CartRepository constructor.
     *
     * @param CacheStorageInterface     $cache
     * @param CountriesServiceInterface $countriesService
     * @param ProductRepository         $productRepository
     * @param CouponRepository          $couponRepository
     * @param OrderRepository           $orderRepository
     * @param GiftRepository            $giftRepository
     * @param TaxRateRepository         $taxRateRepository
     * @param BlockedProductsInterface  $blockedProducts
     */
    public function __construct(
        CacheStorageInterface     $cache,
        CountriesServiceInterface $countriesService,
        ProductRepository         $productRepository,
        CouponRepository          $couponRepository,
        OrderRepository           $orderRepository,
        GiftRepository            $giftRepository,
        TaxRateRepository         $taxRateRepository,
        BlockedProductsInterface  $blockedProducts
    )
    {
        $this->cache             = $cache;
        $this->countriesService  = $countriesService;
        $this->productRepository = $productRepository;
        $this->couponRepository  = $couponRepository;
        $this->orderRepository   = $orderRepository;
        $this->giftRepository    = $giftRepository;
        $this->taxRateRepository = $taxRateRepository;
        $this->blockedProducts   = $blockedProducts;
    }

    /**
     * @param string $storageKey
     *
     * @return CartRepository
     */
    public function setStorageKey(string $storageKey): self
    {
        $this->storageKey = $storageKey;

        return $this;
    }

    /**
     * @param bool $shortTimePersistence
     *
     * @return CartRepository
     */
    public function setShortTimePersistence(bool $shortTimePersistence): self
    {
        $this->shortTimePersistence = $shortTimePersistence;

        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return CartRepository
     */
    public function setUser(User $user = null): CartRepository
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Cart
     */
    public function get(): Cart
    {
//        $this->clearCart();
        /** @var Cart $cart */
        $cart = $this->cache->get($this->storageKey);
        if (empty($cart)) {
            $cart = Cart::make();
            $this->store($cart);
        }

        return $this->checkCart($cart);
    }

    /**
     * @param int $productId
     * @param int $quantity
     * @param string|null $section
     *
     * @return Cart
     * @throws ResourceNotFoundException
     */
    public function addItem(int $productId, int $quantity, ?string $section): Cart
    {
        /** @var Product $product */
        $product = $this->productRepository->getById($productId);
        if (empty($product)) {
            throw new ResourceNotFoundException;
        }

        $item = Item::make($this->prepareProductForItem($product), $quantity, $section);
        $cart = $this->get()->setItem($item);
        $cart = $this->store($cart->update());

        event(new CartItemChangedEvent($productId));

        return $cart;
    }

    /**
     * @param int $id
     * @param int $quantity
     *
     * @return Cart
     */
    public function updateItem(int $id, int $quantity): Cart
    {
        $cart = $this->get();
        if (empty($item = $cart->getItem($id))) {
            return $cart;
        }

        $cart = $this->checkCurrentCoupon($cart->setItem($item->setQuantity($quantity))->update());
        $cart = $this->store($cart->update());

        event(new CartItemChangedEvent($id, CrudActions::ACTION_UPDATED));

        return $cart;
    }

    /**
     * @param int $id
     *
     * @return Cart
     */
    public function removeItem(int $id): Cart
    {
        $cart = $this->get();
        if (empty($item = $cart->getItem($id))) {
            return $cart;
        }

        $cart = $this->checkCurrentCoupon($cart->removeItem($id)->update());

        $items = $cart->getItems();
        if (!count($items)) {
            $this->clearCart();
        }

        $cart = $this->store($cart->update());

        event(new CartItemChangedEvent($id, CrudActions::ACTION_REMOVED));

        return $cart;
    }

    /**
     * @param string $code
     *
     * @return Cart
     * @throws InvalidGiftException
     * @throws CommonException
     */
    public function setGift(string $code): Cart
    {
        $cart = $this->get();
        if ($cart->getTotal() === 0) {
            throw new CommonException('The gift card cannot be applied. Total is already 0.');
        }

        $gift = null;
        if (!empty($code)) {
            /** @var Gift $gift */
            $gift = $this->giftRepository->first([
                'code'      => $code,
                'enabled'   => 1,
                'checkDate' => Carbon::now()
            ]);
        }

        if (empty($gift)) {
            throw new InvalidGiftException();
        }

        return $this->store($cart->setGift($gift)->update());
    }

    /**
     * @param string $code
     *
     * @return Cart
     * @throws InvalidCouponException
     * @throws CommonException
     */
    public function setCoupon(string $code): Cart
    {
        $coupon = null;
        $cart   = $this->get();
        if ($cart->getTotal() === 0) {
            throw new CommonException('The coupon cannot be applied. Total is already 0.');
        }

        if (!empty($code)) {
            $response = $this->checkCouponIsApplicable($cart, $code);
            if (!$response->isSuccess()) {
                throw new InvalidCouponException($response->getMessage());
            }

            $coupon = $response->getValue();
        }

        return $this->store($cart->setCoupon($coupon)->update());
    }

    /**
     * @param Location|null $location
     *
     * @return Cart
     * @throws ResourceNotFoundException
     */
    public function setTaxRate(Location $location = null): Cart
    {
        $cart = $this->get();
        if (empty($location->getCountryCode())) {
            return $cart;
        }

        $country = $this->countriesService->getCountryByCode($location->getCountryCode());
        if (empty($country)) {
            throw new ResourceNotFoundException;
        }

        /** @var TaxRate $taxRate */
        $taxRate = $this->taxRateRepository->getById($country->code);

        $cart
            ->setLocation($location)
            ->setTaxRate($taxRate);

        return $this->store($cart->update());
    }

    /**
     * @param array $data
     *
     * @return Cart
     */
    public function setBilling(array $data = []): Cart
    {
        $cart = $this->get();

        if (empty($billing = $cart->getBilling())) {
            $billing = Billing::make($data);
        } else {
            $billing->setBulkData($data);
        }

        return $this->store($cart->setBilling($billing));
    }

    /**
     * @return bool
     */
    public function clearCart(): bool
    {
        return $this->cache->forget($this->storageKey);
    }

    /**
     * @param string $fromKey
     *
     * @return Cart
     */
    public function copyFromStorageKey(string $fromKey): ?Cart
    {
        /** @var Cart $cart */
        $cart = $this->cache->get($fromKey);
        if (!empty($cart) && $cart->getProductsCounter()) {
            $this->cache->forget($fromKey);
            $this->store($cart);
        }

        return $cart;
    }

    /**
     * @return Carbon
     */
    private function getStorageTime(): Carbon
    {
        return $this->shortTimePersistence
            ? Carbon::now()->addHour(1)
            : Carbon::now()->addWeek(4);
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function store(Cart $cart): Cart
    {
        $this->cache->put($this->storageKey, $cart->setTimestamp(), $this->getStorageTime());

        return $cart;
    }

    /**
     * @param Cart   $cart
     * @param string $code
     *
     * @return SimpleResponse
     */
    private function checkCouponIsApplicable(Cart $cart, string $code = null): SimpleResponse
    {
        if (empty($code)) {
            return SimpleResponse::make('Invalid coupon to check');
        }

        /** @var Coupon $coupon */
        $coupon = $this->couponRepository->first([
            'code'      => $code,
            'checkDate' => Carbon::now()
        ]);

        if (empty($coupon)) {
            return SimpleResponse::make('Unknown or expired coupon!');
        }

        if ($coupon->firstPurchase) {
            if (!$this->user) {
                return SimpleResponse::make('You need to be logged in order to use this coupon.');
            }

            $hasOrders = $this->orderRepository->hasOrders($this->user);
            if ($hasOrders) {
                return SimpleResponse::make('This coupon is only available for the first order');
            }
        }

        if ($coupon->usageLimit && ($coupon->usageLimit <= $coupon->usages)) {
            return SimpleResponse::make('This coupon is no longer available to use.');
        }

        if ($coupon->minAmount && ($coupon->minAmount > $cart->getSubTotal())) {
            return SimpleResponse::make('The minimum amount to use this coupon is ' . $coupon->toFloatAmount($coupon->minAmount) . '.');
        }

        if ($coupon->uniqueOnUser) {
            if (!$this->user) {
                return SimpleResponse::make('You need to be logged in order to use this coupon.');
            }

            if (!$this->couponRepository->checkUserUsage($coupon, $this->user)) {
                return SimpleResponse::make('You have already used this coupon.');
            }
        }

        if (!$coupon->products->isEmpty()) {
            $productFound = false;
            $coupon->products->each(function (Product $product) use ($coupon, $cart, &$productFound) {
                if ($item = $cart->getItem($product->id)) {
                    $productFound = !($coupon->excludeDiscounts && $item->getProduct()->hasDiscount());
                    if ($productFound) {
                        return false;
                    }
                }
            });

            if (!$productFound) {
                return SimpleResponse::make('This coupon is not applicable for the current products.');
            }
        } elseif ($coupon->excludeDiscounts) {
            $canBeApplied = false;
            foreach ($cart->getItems() as $item) {
                if (!$item->getProduct()->hasDiscount()) {
                    $canBeApplied = true;
                    break;
                }
            }

            if (!$canBeApplied) {
                return SimpleResponse::make('This coupon is not applicable for the current products.');
            }
        }

        return SimpleResponse::make('', true, $coupon);
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function checkCart(Cart $cart): Cart
    {
        $shouldStore = false;

        $cart = $this->checkCurrentItems($cart);
        $cart = $this->checkCurrentGifts($cart);
        if ($cart->hasUpdates()) {
            $cart->update();
            $shouldStore = true;
        }

        $cart = $this->checkCurrentTaxRate($cart);

        $cart = $this->checkCurrentCoupon($cart);
        if ($cart->hasUpdates()) {
            $cart->update();
            $shouldStore = true;
        }

        return $shouldStore ? $this->store($cart) : $cart;
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function checkCurrentItems(Cart $cart): Cart
    {
        foreach ($cart->getItems() as $item) {
            $productId  = $item->getProduct()->id;
            $cacheModel = CacheModel::make($this->productRepository)->setEntityId($item->getProduct()->id);
            if (!$cacheModel->isValid($cart->getTimestamp())) {
                /** @var Product $product */
                $product = $this->productRepository->getById($productId);
                if (empty($product)) {
                    $cart->removeItem($item->getKey());
                } else {
                    $cart->setItem($item->setProduct($this->prepareProductForItem($product)));
                }
            }
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function checkCurrentGifts(Cart $cart): Cart
    {
        $codes = array_keys($cart->getGifts());
        if (!empty($codes)) {
            /** @var Gift[] $gifts */
            $gifts = $this->giftRepository->get([
                'code'      => $codes,
                'enabled'   => 1,
                'checkDate' => Carbon::now()
            ]);

            $cart->removeGifts();

            foreach ($gifts as $gift) {
                $cart->setGift($gift, false);
            }

            $cart->sortGifts();
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function checkCurrentCoupon(Cart $cart): Cart
    {
        if (!empty($coupon = $cart->getCoupon())) {
            $response = $this->checkCouponIsApplicable($cart, $coupon->code);
            if (!$response->isSuccess()) {
                $cart->setCoupon(null);
            }
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     *
     * @return Cart
     */
    private function checkCurrentTaxRate(Cart $cart): Cart
    {
        if (!empty($taxRate = $cart->getTaxRate())) {
            $countryCode = $taxRate->code;
            $cacheModel  = CacheModel::make($this->taxRateRepository)->setEntityId($countryCode);
            if (!$cacheModel->isValid($cart->getTimestamp())) {
                /** @var TaxRate $taxRate */
                $taxRate = $this->taxRateRepository->getById($countryCode);
                $cart->setTaxRate($taxRate);
            }
        }

        return $cart;
    }

    private function prepareProductForItem(Product $product): Product
    {
        if ($product->isBundle) {
            $boughtProducts = $this->blockedProducts->getProducts();
            foreach ($product->children as $key => $child) {
                if (in_array($child->id, $boughtProducts)) {
                    $product->children->forget($key);
                }
            }

            $product->calculateBundlePrice();
        }

        return $product;
    }
}
