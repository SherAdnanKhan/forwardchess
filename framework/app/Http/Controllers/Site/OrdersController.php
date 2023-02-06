<?php

namespace App\Http\Controllers\Site;

use App\Assets\SortBy;
use App\Models\Order\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

/**
 * Class OrdersController
 * @package App\Http\Controllers\Site
 */
class OrdersController extends AbstractSiteController
{
    /**
     * Show the application dashboard.
     *
     * @param Request      $request
     * @param OrderService $ordersService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, OrderService $ordersService)
    {
        // $orders = $ordersService->all(['userId' => $request->user()->id], SortBy::make('created_at', 'desc')); // old code
        $this->addViewData([
            'orders' => collect($ordersService->getOrderFromMobile($request->user()->email))
        ]);

        return view('site.pages.orders', $this->viewData);
    }

    /**
     * Show the application dashboard.
     *
     * @param OrderService $ordersService
     *
     * @return \Illuminate\Http\Response
     */
    public function show(OrderService $ordersService)
    {
        /** @var Order $order */
        $order = $ordersService->getRequest()->getModel();
        $order->load(['billing', 'items']);

        $this->addViewData([
            'order' => $order
        ]);

        return view('site.pages.order', $this->viewData);
    }
}