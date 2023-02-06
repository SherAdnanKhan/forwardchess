<?php

namespace App\MailChimp;

use App\Assets\MailChimp\CartAction;
use App\Assets\MailChimp\ProductAction;
use App\Common\CrudActions;
use App\Contracts\MailChimpServiceInterface;
use App\MailChimp\Services\AutomationService;
use App\MailChimp\Services\EcommerceService;
use App\MailChimp\Services\EventsService;
use App\MailChimp\Services\ListService;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use MailchimpMarketing\ApiClient;

/**
 * Class MailChimpService
 * @package App\MailChimp
 */
class MailChimpService implements MailChimpServiceInterface
{
    /**
     * @var ApiClient
     */
    private ApiClient $apiClient;

    /**
     * @var array
     */
    private array $config;

    /**
     * MailChimpService constructor.
     *
     * @param ApiClient $apiClient
     */
    public function __construct(ApiClient $apiClient)
    {
        $this->config = config('mailchimp');

        $this->apiClient = $apiClient;
        $this->apiClient->setConfig([
            'apiKey' => $this->config['apiKey'],
            'server' => $this->config['server']
        ]);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function test()
    {
        /** @var User $user */
        $user = Auth::user();

        dd(
            $this->listService()->getSegmentMembers(
                $this->config['lists']['subscribers'],
                $this->config['automations']['browseAbandoned']['segmentId']
            )
        );

//        $this->listService()->updateSubscribers($this->config['lists']['subscribers']);
//        return $this->ecommerceService()->productRepository()->exists(1000);
//        return $this->ecommerceService()->cartRepository()->destroy(3025);
        return $this->ecommerceService()->getCarts();
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function registerUser(User $user): bool
    {
        $this->listService()->subscribeUserToList($this->config['lists']['registration'], $user);

        if ($user->subscribed) {
            $this->subscribe($user);
        }

        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function subscribe(User $user): bool
    {
        return $this->listService()->subscribeUserToList($this->config['lists']['subscribers'], $user);
    }

    /**
     * @param User $user
     * @param bool $hardDelete
     *
     * @return bool
     * @throws Exception
     */
    public function unsubscribe(User $user, bool $hardDelete = false): bool
    {
        return $this->listService()->unsubscribeUserFromList($this->config['lists']['subscribers'], $user, $hardDelete);
    }

    /**
     * @param ProductAction $action
     *
     * @return bool
     * @throws Exception
     */
    public function syncProduct(ProductAction $action): bool
    {
        return ($action->getName() === CrudActions::ACTION_REMOVED)
            ? $this->ecommerceService()->deleteProduct($action->getProduct())
            : $this->ecommerceService()->updateProduct($action->getProduct());
    }

    /**
     * @param CartAction $action
     *
     * @return bool
     * @throws Exception
     */
    public function syncCartItem(CartAction $action): bool
    {
        return ($action->getName() === CrudActions::ACTION_REMOVED)
            ? $this->ecommerceService()->deleteCartItem($action)
            : $this->ecommerceService()->updateCartItem($action);
    }

    /**
     * @param User    $user
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function productAbandoned(User $user, Product $product): bool
    {
        $dataSaved = $this->listService()->saveAbandonedData(
            $this->config['lists']['subscribers'],
            $user,
            $product
        );

        return $dataSaved && $this->automationsService()->store($user);
    }

    /**
     * @param Order $order
     *
     * @return bool
     * @throws Exception
     */
    public function clearUserAutomations(Order $order): bool
    {
        $this->automationsService()->destroy($order->user);

        return $this->ecommerceService()->deleteCart($order);
    }

    /**
     * @return ListService
     */
    private function listService(): ListService
    {
        return new ListService($this->apiClient->lists, $this->config);
    }

    /**
     * @return EcommerceService
     */
    private function ecommerceService(): EcommerceService
    {
        return new EcommerceService($this->apiClient->ecommerce, $this->config);
    }

    /**
     * @return EventsService
     */
    private function eventsService(): EventsService
    {
        return new EventsService($this->apiClient->lists, $this->config);
    }

    /**
     * @return AutomationService
     */
    private function automationsService(): AutomationService
    {
        return new AutomationService(
            $this->listService(),
            $this->eventsService(),
            $this->apiClient->automations,
            $this->config
        );
    }
}
