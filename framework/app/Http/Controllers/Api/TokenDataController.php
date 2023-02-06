<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TokenDataService;
use Illuminate\Http\Request;

class TokenDataController extends Controller
{

    /**
     * @var TokenDataService $service
     */
    protected $service;

    public function __construct(TokenDataService $service){
        $this->service = $service;
    }

    public function generate(Request $request){
        return $this->service->getToken($request);
    }

    public function getuser(Request $request) {
        return $request->user();
    }
}
