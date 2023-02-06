<?php

namespace App\Services;

use App\Repositories\TokenDataRepository;
use Illuminate\Http\Request;

class TokenDataService
{

    /**
     * @var TokenDataRepository $repository
     */
    private $repository;

    public function __construct(TokenDataRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getToken(Request $request){
        return $this->repository->getToken($request);
    }
}
