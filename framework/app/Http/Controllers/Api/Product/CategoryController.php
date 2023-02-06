<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\CategoryResource;
use App\Services\Product\CategoryService;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Api\Product
 */
class CategoryController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = CategoryResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(CategoryService $service)
    {
        $response = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CategoryService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param $method
     * @param array ...$params
     *
     * @return mixed
     */
    private function resource($method, ...$params)
    {
        return forward_static_call_array([$this->resourceClass, $method], $params);
    }
}
