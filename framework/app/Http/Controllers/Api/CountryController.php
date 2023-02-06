<?php

namespace App\Http\Controllers\Api;

use App\Contracts\CountriesServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Http\Resources\StateResource;

/**
 * Class CountryController
 * @package App\Http\Controllers\Api
 */
class CountryController extends Controller
{
    /**
     * @param CountriesServiceInterface $service
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCountries(CountriesServiceInterface $service)
    {
        return CountryResource::collection($service->getCountries()->values());
    }

    /**
     * @param CountriesServiceInterface $service
     * @param                           $country
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getStates(CountriesServiceInterface $service, $country)
    {
        return StateResource::collection($service->getStates($country)->values());
    }
}
