<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\PaymentResource;
use App\Services\Order\PaymentService;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Api\Order
 */
class PaymentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  PaymentService $service
     *
     * @return PaymentResource
     */
    public function show(PaymentService $service)
    {
        return PaymentResource::make($service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PaymentService $service
     *
     * @return PaymentResource
     */
    public function update(PaymentService $service)
    {
        return PaymentResource::make($service->update());
    }
}
