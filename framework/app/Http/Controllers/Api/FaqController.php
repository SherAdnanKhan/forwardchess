<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Services\FaqService;

/**
 * Class FaqController
 * @package App\Http\Controllers\Api
 */
class FaqController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = FaqResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories(FaqService $service)
    {
        return response()->json(['data' => $service->getCategories()]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FaqService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(FaqService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(FaqService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(FaqService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(FaqService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FaqService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(FaqService $service)
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
