<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\ItemResource;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderService;

/**
 * Class OrderController
 * @package App\Http\Controllers\Api\Order
 */
class OrderController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = OrderResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderService $service)
    {
        return $this->resource('collection', $service->paginate());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(OrderService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(OrderService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(OrderService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(OrderService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  OrderService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(OrderService $service)
    {
        return $this->resource('make', $service->restore());
    }

    /**
     * @param OrderService $service
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function items(OrderService $service)
    {
        return ItemResource::collection($service->getItems());
    }

    /**
     * @param       $method
     * @param array ...$params
     *
     * @return mixed
     */
    private function resource($method, ...$params)
    {
        return forward_static_call_array([$this->resourceClass, $method], $params);
    }
}
