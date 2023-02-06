<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\PublisherResource;
use App\Services\Product\PublisherService;

/**
 * Class PublisherController
 * @package App\Http\Controllers\Api\Product
 */
class PublisherController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = PublisherResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PublisherService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(PublisherService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PublisherService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(PublisherService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PublisherService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PublisherService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(PublisherService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
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
