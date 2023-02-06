<?php

namespace App\Http\Controllers\Site;

use App\Common\PaypalPayment;
use App\Contracts\CacheStorageInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\PaypalPaymentInterface;
use App\Events\OrderCompletedEvent;
use App\Exceptions\CommonException;
use App\Models\Order\Order;
use App\Repositories\Order\OrderRepository;
use App\Services\Order\OrderService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use PayPal\Api\CreateProfileResponse;
use PayPal\Api\FlowConfig;
use PayPal\Api\WebProfile;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Site
 */
class PaymentController extends AbstractSiteController
{
    const PAYMENT_SESSION_KEY = 'paypal_payment_id';
    /**
     * @var CacheStorageInterface
     */
    private $cacheStorage;

    /**
     * @var ApiContext
     */
    private $apiContext;

    /**
     * @var CreateProfileResponse
     */
    private $webProfile;

    /**
     * PaymentController constructor.
     *
     * @param CacheStorageInterface $cacheStorage
     */
    public function __construct(CacheStorageInterface $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;

        $this->init();
    }

    /**
     * @param Order $order
     *
     * @return null|string
     */
    private function getPaymentUrl(Order $order)
    {
        /** @var PaypalPayment $paypalPayment */
        $paypalPayment = app(PaypalPaymentInterface::class)
            ->setOrder($order)
            ->setProfileID($this->webProfile->getId())
            ->createPayment($this->apiContext);

        Session::put(self::PAYMENT_SESSION_KEY, $paypalPayment->getPaymentID());

        return $paypalPayment->getRedirectUrl();
    }

    /**
     * @param CartServiceInterface $cartService
     * @param OrderService         $service
     *
     * @return JsonResponse
     * @throws CommonException
     */
    public function placeOrder(CartServiceInterface $cartService, OrderService $service): JsonResponse
    {
        /** @var Order $order */
        $order = $service->placeOrder($cartService);

        $redirectUrl = ($order->total > 0)
            ? $this->getPaymentUrl($order)
            : $this->finalizeOrder($order);

        return response()->json(['url' => $redirectUrl]);
    }

    /**
     * @param OrderService $service
     *
     * @return JsonResponse
     * @throws CommonException
     */
    public function placeGiftOrder(OrderService $service): JsonResponse
    {
        /** @var Order $order */
        $order = $service->placeGiftOrder();

        $redirectUrl = ($order->total > 0)
            ? $this->getPaymentUrl($order)
            : $this->finalizeOrder($order);

        return response()->json(['url' => $redirectUrl]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function updatePayment(Request $request): RedirectResponse
    {
        /** Get the payment ID before session clear **/
        $orderInfo = $request->session()->get(CartServiceInterface::LAST_ORDER_SESSION_KEY);
        $paymentId = $request->session()->pull(self::PAYMENT_SESSION_KEY);
        $payerId   = $request->input('PayerID');
        $token     = $request->input('token');

        if (!(empty($orderInfo) || empty($paymentId) || empty($payerId) || empty($token))) {
            try {
                /** @var PaypalPaymentInterface $paypalPayment */
                $paypalPayment = app(PaypalPaymentInterface::class);
                $payment       = $paypalPayment->getPaymentDetails($this->apiContext, $paymentId, $payerId);

                if ($payment->getState() == 'approved') {
                    return $this->updateOrder($request, $orderInfo['id'], $paypalPayment->getOrderPaymentDetails($payment));
                }
            } catch (\Exception $e) {
//            dd($e);
            }
        }

        return redirect()->route('site.retryPayment');
    }

    /**
     * @param Request         $request
     * @param OrderRepository $orderRepository
     *
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function retryPayment(Request $request, OrderRepository $orderRepository)
    {
        $redirectRoute = redirect(route('site.home'));
        $lastOrder     = $request->session()->get(CartServiceInterface::LAST_ORDER_SESSION_KEY);
        if (empty($lastOrder)) {
            return $redirectRoute;
        }

        /** @var Order $order */
        $order = $orderRepository->getById($lastOrder['id']);
        if (empty($order)) {
            return $redirectRoute;
        }

        $paypalUrl = $this->getPaymentUrl($order);

        $this->addViewData([
            'order'     => $order,
            'paypalUrl' => $paypalUrl,
        ]);

        return view('site.pages.retry-payment', $this->viewData);
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $config           = config('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential($config['credentials']['id'], $config['credentials']['secret']));
        $this->apiContext->setConfig($config['settings']);
        $this->webProfile = $this->cacheStorage->remember('webProfile_' . env('PAYPAL_MODE'), 60 * 3, function () {
            return $this->createWeProfile();
        });
    }

    /**
     * @return CreateProfileResponse
     */
    private function createWeProfile(): CreateProfileResponse
    {
        $flowConfig = new FlowConfig();
// Type of PayPal page to be displayed when a user lands on the PayPal site for checkout. Allowed values: Billing or Login. When set to Billing, the Non-PayPal account landing page is used. When set to Login, the PayPal account login landing page is used.
        $flowConfig->setLandingPageType("Billing");

// When set to "commit", the buyer is shown an amount, and the button text will read "Pay Now" on the checkout page.
        $flowConfig->setUserAction("commit");

// Parameters for input fields customization.
        $inputFields = new \PayPal\Api\InputFields();
// Enables the buyer to enter a note to the merchant on the PayPal page during checkout.
        $inputFields->setAllowNote(true)
            // Determines whether or not PayPal displays shipping address fields on the experience pages. Allowed values: 0, 1, or 2. When set to 0, PayPal displays the shipping address on the PayPal pages. When set to 1, PayPal does not display shipping address fields whatsoever. When set to 2, if you do not pass the shipping address, PayPal obtains it from the buyer’s account profile. For digital goods, this field is required, and you must set it to 1.
            ->setNoShipping(1)
            // Determines whether or not the PayPal pages should display the shipping address and not the shipping address on file with PayPal for this buyer. Displaying the PayPal street address on file does not allow the buyer to edit that address. Allowed values: 0 or 1. When set to 0, the PayPal pages should not display the shipping address. When set to 1, the PayPal pages should display the shipping address.
            ->setAddressOverride(0);

        $webProfile = new WebProfile();
        $webProfile
            ->setName(env('APP_NAME') . uniqid())
            ->setFlowConfig($flowConfig)
            ->setInputFields($inputFields)
            // Indicates whether the profile persists for three hours or permanently. Set to `false` to persist the profile permanently. Set to `true` to persist the profile for three hours.
            ->setTemporary(true);

        return $webProfile->create($this->apiContext);
    }

    /**
     * @param Request    $request
     * @param int        $orderId
     * @param array|null $payment
     *
     * @return RedirectResponse
     */
    private function updateOrder(Request $request, int $orderId, array $payment = null): RedirectResponse
    {
        try {
            /** @var OrderRepository $orderRepository */
            $orderRepository = app(OrderRepository::class);

            /** @var Order $order */
            $order = $orderRepository->first(['id' => $orderId]);
            if (empty($order)) {
                $request->session()->flash('paymentError', 'Payment failed');
            }

            empty($order->payment)
                ? $order->payment()->create($payment)
                : $order->payment->update($payment);

            $this->finalizeOrder($order);
        } catch (\Exception $e) {
            $request->session()->flash('paymentError', 'Payment failed');
        }

        return redirect()->route('site.thanks');
    }

    /**
     * @param Order $order
     *
     * @return string
     * @throws CommonException
     */
    private function finalizeOrder(Order $order): string
    {
        /** @var OrderRepository $orderRepository */
        $orderRepository = app(OrderRepository::class);

        $orderRepository->update($order, [
            'status'        => Order::STATUS_COMPLETED,
            'allowDownload' => 1,
            'completedDate' => Carbon::now(),
            'paidDate'      => Carbon::now()
        ]);

        event(new OrderCompletedEvent($order));

        return route('site.thanks');
    }
}