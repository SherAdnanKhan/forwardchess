<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\ArticleCollection;
use App\Http\Resources\Blog\ArticleResource;
use App\Services\Blog\ArticleService;

/**
 * Class ArticleController
 * @package App\Http\Controllers\Api\Blog
 */
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ArticleService $service)
    {
        return $this->resource('collection', $service->allItems());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(ArticleService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function store(ArticleService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CommonException
     */
    public function update(ArticleService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ArticleService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param ArticleService $service
     *
     * @return mixed
     * @throws \App\Exceptions\CommonException
     */
    public function restore(ArticleService $service)
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
            ? new ArticleCollection($data)
            : new ArticleResource($data);
    }
}
