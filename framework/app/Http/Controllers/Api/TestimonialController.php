<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Services\TestimonialService;

/**
 * Class TestimonialController
 * @package App\Http\Controllers\Api
 */
class TestimonialController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = TestimonialResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TestimonialService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(TestimonialService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TestimonialService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(TestimonialService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TestimonialService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TestimonialService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestimonialService $service)
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
