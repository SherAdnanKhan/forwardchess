<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponCollection;
use App\Http\Resources\CouponResource;
use App\Services\CouponService;

/**
 * Class CouponController
 * @package App\Http\Controllers\Api
 */
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CouponService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(CouponService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(CouponService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CouponService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(CouponService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CouponService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CouponService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param       $method
     * @param mixed $data
     *
     * @return \Illuminate\Http\Response
     */
    private function resource($method, $data)
    {
        return ($method === 'collection')
            ? new CouponCollection($data)
            : new CouponResource($data);
    }
}
