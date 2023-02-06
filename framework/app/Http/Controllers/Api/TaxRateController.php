<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxRateResource;
use App\Services\TaxRateService;
use Illuminate\Http\Response;

/**
 * Class TaxRateController
 * @package App\Http\Controllers\Api
 */
class TaxRateController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = TaxRateResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param TaxRateService $service
     *
     * @return Response
     */
    public function index(TaxRateService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param TaxRateService $service
     *
     * @return Response
     */
    public function tables(TaxRateService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param TaxRateService $service
     *
     * @return Response
     */
    public function show(TaxRateService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaxRateService $service
     *
     * @return Response
     */
    public function update(TaxRateService $service)
    {
        return $this->resource('make', $service->update());
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
