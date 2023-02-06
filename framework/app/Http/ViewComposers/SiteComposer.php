<?php

namespace App\Http\ViewComposers;

use App\Common\DataCollector;
use App\Contracts\EcommerceInterface;
use App\Contracts\MobileGatewayInterface;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

/**
 * Class SiteComposer
 * @package App\Http\ViewComposers
 */
class SiteComposer
{
    /**
     * @var DataCollector
     */
    private DataCollector $dataCollector;

    /**
     * @var EcommerceInterface
     */
    private EcommerceInterface $ecommerceService;

    /**
     * @var MobileGatewayInterface
     */
    private MobileGatewayInterface $mobileGateway;

    /**
     * SiteComposer constructor.
     *
     * @param DataCollector          $dataCollector
     * @param EcommerceInterface     $ecommerceService
     * @param MobileGatewayInterface $mobileGateway
     */
    public function __construct(DataCollector $dataCollector, EcommerceInterface $ecommerceService, MobileGatewayInterface $mobileGateway)
    {
        $this->dataCollector    = $dataCollector;
        $this->ecommerceService = $ecommerceService;
        $this->mobileGateway    = $mobileGateway;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $globalData = $this->dataCollector->getGlobalData();

        $view->with('cart', $globalData->cart);
        $view->with('publishers', $globalData->publishers);
        $view->with('categories', $globalData->categories);
        $view->with('analytics', $this->ecommerceService->getData());

        // generate a new firebase token, if it has been expired
        if (auth()->check() && Session::has('firebase_token') && Session::get('firebase_token')['expire_at']->diffInMinutes(now()) > 60) {
            $this->mobileGateway->getFirebaseToken(auth()->user()->email);
        }

        JavaScriptFacade::put([
            'apiBaseURL' => Request::root(),
            'cart'       => $globalData->cart->toArray(),
            'wishlist'   => $globalData->wishlist
        ]);
    }
}
