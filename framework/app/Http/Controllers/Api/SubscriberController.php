<?php

namespace App\Http\Controllers\Api\Subscriber;

use App\Services\SubscriberService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\ItemResource;
use App\Http\Resources\SubscriberResource;

/**
 * Class SubscriberController
 * @package App\Http\Controllers\Api\Subscriber
 */
class SubscriberController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = SubscriberResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubscriberService $service)
    {
        return $this->resource('collection', $service->paginate());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(SubscriberService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(SubscriberService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriberService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(SubscriberService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Responseecho $abc;
     */
    public function destroy(SubscriberService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SubscriberService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(SubscriberService $service)
    {
        return $this->resource('make', $service->restore());
    }

    /**
     * @param SubscriberService $service
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function items(SubscriberService $service)
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
