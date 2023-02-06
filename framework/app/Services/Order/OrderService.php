<?php

namespace App\Services\Order;

use App\Assets\GiftCard;
use App\Assets\PlaceOrder;
use App\Contracts\CartServiceInterface;
use App\Contracts\MobileGatewayInterface;
use App\Exceptions\CommonException;
use App\Http\Requests\Order\OrderRequest;
use App\Models\AbstractModel;
use App\Models\Order\Item;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use App\Searches\OrderSearch;
use App\Services\AbstractService;
use App\Services\Tables;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class OrderService
 * @package App\Services\Order
 *
 * @property OrderRepository repository
 */
class OrderService extends AbstractService
{
    use Tables;

    /**
     * @var MobileGatewayInterface
     */
    private MobileGatewayInterface $mobileGateway;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * OrderService constructor.
     *
     * @param OrderRequest           $request
     * @param Guard                  $auth
     * @param OrderRepository        $repository
     * @param MobileGatewayInterface $mobileGateway
     * @param ProductRepository      $productRepository
     */
    public function __construct(OrderRequest $request, Guard $auth, OrderRepository $repository, MobileGatewayInterface $mobileGateway, ProductRepository $productRepository)
    {
        parent::__construct($request, $auth, $repository);

        $this->mobileGateway     = $mobileGateway;
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        $searchFields = [
            'id'       => 'orders.id',
            'refNo'    => 'orders.refNo',
            'email'    => 'order_billing.email',
            'fullName' => 'order_billing.firstName',
        ];

        $builder = $this
            ->repository
            ->getBuilder($this->initCollectionFilters());

        return $this->getTable(
            $this->request,
            $builder,
            $searchFields
        );
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        /** @var Order $order */
        $order = $this->request->getModel();

        return $order->items;
    }

    /**
     * @param CartServiceInterface $cartService
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function placeOrder(CartServiceInterface $cartService): AbstractModel
    {
        $cart       = $cartService->get();
        $placeOrder = new PlaceOrder;

        $placeOrder->setDetails([
            'userId'    => $this->request->user()->id,
            'subTotal'  => $cart->getSubTotal(),
            'discount'  => $cart->getDiscount(),
            'total'     => $cart->getTotal(),
            'ipAddress' => $this->request->ip(),
            'userAgent' => $this->request->userAgent()
        ]);

        if (!empty($taxRate = $cart->getTaxRate())) {
            $placeOrder->setDetails([
                'taxRateCountry' => $taxRate->code,
                'taxRateAmount'  => $taxRate->rate,
                'taxAmount'      => $cart->getTax(),
            ]);
        }

        if (!empty($coupon = $cart->getCoupon())) {
            $placeOrder->setDetails([
                'coupon' => $coupon->code
            ]);
        }

        $billing = $cart->getBilling();

        $billingDetails = [
            'firstName' => $this->request->get('firstName'),
            'lastName'  => $this->request->get('lastName'),
            'email'     => $billing->getEmail()
        ];

        if (!empty($location = $cart->getLocation())) {
            $billingDetails = array_merge($billingDetails, [
                'country'  => $location->getCountryCode(),
                'state'    => $location->getRegionName(),
                'city'     => $location->getCity(),
                'postcode' => $location->getZip(),
                'location' => $location->toJson()
            ]);
        }

        $cartService->setBilling($billingDetails);

        $placeOrder
            ->setItems($cart->getItems())
            ->setGifts($cart->getGifts())
            ->setBilling($billingDetails)
            ->setCoupon($coupon);

        $order = $this->repository->placeOrder($placeOrder);

        $cartService->cleanCart();

        return $this->addOrderToSession($order);
    }

    /**
     * @return AbstractModel
     * @throws CommonException
     */
    public function placeGiftOrder(): AbstractModel
    {
        /** @var GiftCard $gift */
        $gift = ($this->request->hasSession() && $this->request->session()->has(CartServiceInterface::GIFT_CARD_KEY))
            ? $this->request->session()->get(CartServiceInterface::GIFT_CARD_KEY)
            : null;

        if (empty($gift)) {
            throw new CommonException('Invalid gift card details');
        }

        $amount     = toIntAmount($gift->getAmount());
        $placeOrder = (new PlaceOrder)
            ->setDetails([
                'userId'    => $this->request->user()->id,
                'subTotal'  => $amount,
                'total'     => $amount,
                'ipAddress' => $this->request->ip(),
                'userAgent' => $this->request->userAgent(),
            ])
            ->setBilling([
                'email'     => $this->request->user()->email,
                'firstName' => $this->request->get('firstName'),
                'lastName'  => $this->request->get('lastName'),
            ])
            ->setGiftCard($gift);

        /** @var OrderRepository $repository */
        $repository = $this->repository;

        /** @var Order $order */
        $order = $repository->placeGiftOrder($placeOrder);

        $this->request->session()->forget(CartServiceInterface::GIFT_CARD_KEY);

        return $this->addOrderToSession($order);
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        /** @var OrderSearch $search */
        $search = app(OrderSearch::class, ['data' => $this->request->all()]);

        return array_merge($search->getFilters(), $filters);
    }

    /**
     * @param Order $order
     *
     * @return Order
     */
    private function addOrderToSession(Order $order): Order
    {
        if ($this->request->hasSession()) {
            $this->request->session()->put(CartServiceInterface::LAST_ORDER_SESSION_KEY, [
                'id'      => $order->id,
                'expires' => Carbon::now()->addHour(1)
            ]);

            $this->request->session()->put('orderPlaced', true);
        }

        return $order;
    }

    /**
     * @param Order $order
     *
     * @return Order
     */
    public function getOrderFromMobile($email): array
    {
        $data = [];
        if ($this->request->hasSession()) {
            $data = $this->mobileGateway->getMobilePurchase($email);
            // $data = $this->mobileGatewayInterface->getMobilePurchase('16parrys@kingsmac.co.uk');
        }

        $newData = [];
        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $value['productsData'] = $this
                    ->productRepository
                    ->getBySKU($value['productId']);

                $newData[$key] = $value;
            }
        }

        return $newData;
    }
}