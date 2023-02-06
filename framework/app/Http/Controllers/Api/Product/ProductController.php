<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\ProductService;

/**
 * Class ProductController
 * @package App\Http\Controllers\Api\Product
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductService $service)
    {
        return $this->resource('collection', $service->allItems());
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProductRepository $productRepository
     *
     * @return \Illuminate\Http\Response
     */
    public function desktopPrices(ProductRepository $productRepository)
    {
        $data     = [];
        $products = $productRepository->get();
        foreach ($products as $product) {
            $data[$product->sku] = '$' . toFloatAmount($product->price);
        }

        return response()->json(['message' => $data]);
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(ProductService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(ProductService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(ProductService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProductService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param ProductService $service
     *
     * @return mixed
     * @throws \App\Exceptions\CommonException
     */
    public function restore(ProductService $service)
    {
        return $this->resource('make', $service->update(true));
    }

    /**
     * @param       $method
     * @param mixed $data
     *
     * @return mixed
     */
    private function resource($method, $data)
    {
        return ($method === 'collection')
            ? new ProductCollection($data)
            : new ProductResource($data);
    }
}
