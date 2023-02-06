<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsletterResource;
use App\Services\NewsletterService;

/**
 * Class NewsletterController
 * @package App\Http\Controllers\Api
 */
class NewsletterController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = NewsletterResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NewsletterService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(NewsletterService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(NewsletterService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(NewsletterService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(NewsletterService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NewsletterService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsletterService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    private function resource($method, ...$params)
    {
        return forward_static_call_array([$this->resourceClass, $method], $params);
    }
}
