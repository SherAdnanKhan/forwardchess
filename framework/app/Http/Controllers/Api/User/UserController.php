<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\User
 */
class UserController extends Controller
{
    /**
     * @var string
     */
    private $resourceClass = UserResource::class;

    /**
     * Display a listing of the resource.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserService $service)
    {
        return $this->resource('collection', $service->paginate());
    }

    /**
     * Display a listing of the resource as a table.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function tables(UserService $service)
    {
        $response         = $service->tables();
        $response['data'] = $this->resource('collection', $response['data']);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserService $service)
    {
        return $this->resource('make', $service->store());
    }

    /**
     * Display the specified resource.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function show(UserService $service)
    {
        return $this->resource('make', $service->show());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserService $service)
    {
        return $this->resource('make', $service->update());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserService $service)
    {
        $success = $service->destroy();

        return response()->json(['success' => $success], $success ? 200 : 500);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->resource('make', $user)
            ->setRememberToken($user->remember_token);
    }

    /**
     * @param UserService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function activate(UserService $service)
    {
        return $this->resource('make', $service->activate());
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
