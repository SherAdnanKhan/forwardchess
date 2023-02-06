<?php

namespace App\Http\Controllers\Api\Product;

use App\Exceptions\AuthorizationException;
use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ReviewResource;
use App\Services\Product\ReviewService;
use Illuminate\Http\JsonResponse;

/**
 * Class ReviewController
 * @package App\Http\Controllers\Api\Product
 */
class ReviewController extends Controller
{
    /**
     * @var string
     */
    private string $resourceClass = ReviewResource::class;

    /**
     * @param ReviewService $service
     * @param string        $productId
     *
     * @return mixed
     */
    public function index(ReviewService $service, string $productId)
    {
        return $this->resource('collection', $service->all(['productId' => $productId]));
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param ReviewService $service
     *
     * @return JsonResponse
     */
    public function tables(ReviewService $service): JsonResponse
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewService $service
     *
     * @return mixed
     * @throws CommonException
     * @throws AuthorizationException
     */
    public function store(ReviewService $service)
    {
        return $service->postReview();
    }

    /**
     * Display the specified resource.
     *
     * @param ReviewService $service
     *
     * @return mixed
     */
    public function show(ReviewService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ReviewService $service
     *
     * @return mixed
     * @throws CommonException
     */
    public function update(ReviewService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * @param       $method
     * @param mixed ...$params
     *
     * @return mixed
     */
    private function resource($method, ...$params)
    {
        return forward_static_call_array([$this->resourceClass, $method], $params);
    }
}
