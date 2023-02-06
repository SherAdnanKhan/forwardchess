<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\TagResource;
use App\Services\Blog\TagService;

/**
 * Class TagController
 * @package App\Http\Controllers\Api\Blog
 */
class TagController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = TagResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TagService $service)
    {
        return $this->resource('collection', $service->all());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(TagService $service)
    {
        $response = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TagService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(TagService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TagService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TagService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagService $service)
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
