<?php

namespace App\Http\Controllers\Site;

use App\Contracts\CartServiceInterface;
use App\Contracts\EcommerceInterface;
use App\Contracts\MobileGatewayInterface;
use App\Contracts\ReferralGatewayInterface;
use App\Models\AbTesting;
use App\Models\Order\Item;
use App\Models\Order\Order;
use App\Models\User\User;
use App\Repositories\Order\OrderRepository;
use App\Services\Product\ProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class CartController
 * @package App\Http\Controllers\Site
 */
class CartController extends AbstractSiteController
{
    /**
     * @param CartServiceInterface   $cartService
     * @param ProductService         $productService
     * @param MobileGatewayInterface $mobileGateway
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function shoppingCart(CartServiceInterface $cartService, ProductService $productService, MobileGatewayInterface $mobileGateway)
    {
        $cart = $cartService->get(true);

        if (!$cart->isEmpty()) {
            $skuList = [];
            $items   = $cart->getItems();
            foreach ($items as $item) {
                $product   = $item->getProduct();
                $skuList[] = $product->sku;
            }
            $recommendedBooks = [];
            if (auth()->user()->ab_testing_id == User::AB_TESTING_TYPE_FIRST) {
                $recommendedBooks =  $productService->getRecommendedBooks($skuList, $mobileGateway)->take(0);
            }
            if (auth()->user()->ab_testing_id == User::AB_TESTING_TYPE_SECOND) {
                $recommendedBooks =  $productService->getRecommendedBooks($skuList, $mobileGateway)->take(1);
            }
            if (auth()->user()->ab_testing_id == User::AB_TESTING_TYPE_THIRD) {
                $recommendedBooks =  $productService->getRecommendedBooks($skuList, $mobileGateway)->take(4);
            }

            $this->addViewData([
                'recommendedBooks' => $recommendedBooks,
            ]);
        }

        return view('site.pages.shopping-cart', $this->viewData);
    }

    /**
     * @param CartServiceInterface $cartService
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function checkout(CartServiceInterface $cartService)
    {
        $cart = $cartService->get(true);
        if ($cart->isEmpty()) {
            return redirect(route('site.home'));
        }

        $billing = $cart->getBilling()->toArray();
        if (!empty($location = $cart->getLocation())) {
            $billing = array_merge($billing, ['country' => $location->getCountryName()]);
        }

        $this->addViewData([
            'billing' => json_encode($billing)
        ]);

        app(EcommerceInterface::class)->addCheckout($cart);

        return view('site.pages.checkout', $this->viewData);
    }

    /**
     * @param Request         $request
     * @param OrderRepository $orderRepository
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function thanks(Request $request, OrderRepository $orderRepository)
    {
        $redirectRoute = redirect(route('site.home'));
        $lastOrder     = $request->session()->get(CartServiceInterface::LAST_ORDER_SESSION_KEY);
        if (empty($lastOrder)) {
            return $redirectRoute;
        }

        /** @var Carbon $expires */
        $expires = $lastOrder['expires'];
        if ($expires->diffInSeconds(Carbon::now()) < 0) {
            return $redirectRoute;
        }

        /** @var Order $order */
        $order = $orderRepository->getById($lastOrder['id']);
        if (empty($order)) {
            return $redirectRoute;
        }

        $this->addViewData([
            'order' => $order,
            'pixel' => $this->getPixel($request, $order)
        ]);

        try {
            app(EcommerceInterface::class)->addPurchase($order);
            app(ReferralGatewayInterface::class)->purchase($order, [
                'ip'        => $request->ip(),
                'userAgent' => $request->server('HTTP_USER_AGENT'),
            ]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }

        return view('site.pages.thank-you', $this->viewData);
    }

    private function getPixel(Request $request, Order $order): string
    {
        $sessionKey = 'pixelDisplayed_' . $order->id;
        if ($request->session()->has($sessionKey)) {
            return '';
        }

        $clickId   = $request->cookie('shareasaleSSCID');
        $sscid     = empty($clickId) ? '' : $clickId;
        $skuList   = [];
        $priceList = [];

        foreach ($order->items as $item) {
            $product = $item->detail;

            $skuList[]   = ($item->type === Item::TYPE_PRODUCT) ? $product->sku : 'gift-card';
            $priceList[] = $item->total;
        }

        $params = [
            'tracking'   => $order->refNo,
            'amount'     => toFloatAmount($order->getTotalWithoutTaxes()),
            'merchantID' => '84345',
            'transtype'  => 'sale',
            'sscidmode'  => '6',
            'sscid'      => $sscid,
            'skulist'    => implode(',', $skuList),
            'pricelist'  => implode(',', $priceList),
            'couponcode' => empty($order->coupon) ? '' : $order->coupon,
        ];

        $pixelParams = [];
        foreach ($params as $name => $value) {
            $pixelParams[] = $name . '=' . urlencode($value);
        }

        $pixel = '<img src="https://www.shareasale.com/sale.cfm?' . implode('&', $pixelParams) . '" width="1" height="1"/>';

        $request->session()->put($sessionKey, 1);

        return $pixel;
    }
}
