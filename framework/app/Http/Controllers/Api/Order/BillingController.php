<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\BillingResource;
use App\Services\Order\BillingService;

/**
 * Class BillingController
 * @package App\Http\Controllers\Api\Order
 */
class BillingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  BillingService $service
     *
     * @return BillingResource
     */
    public function show(BillingService $service)
    {
        return BillingResource::make($service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BillingService $service
     *
     * @return BillingResource
     */
    public function update(BillingService $service)
    {
        return BillingResource::make($service->update());
    }
}
