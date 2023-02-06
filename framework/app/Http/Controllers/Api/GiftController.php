<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GiftResource;
use App\Services\GiftService;

/**
 * Class GiftController
 * @package App\Http\Controllers\Api
 */
class GiftController extends Controller
{
    private $resourceClass = GiftResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GiftService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(GiftService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(GiftService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(GiftService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(GiftService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  GiftService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(GiftService $service)
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
