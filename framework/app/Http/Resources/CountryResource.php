<?php

namespace App\Http\Resources;

use App\Assets\Country;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CountryResource
 * @package App\Http\Resources\Product
 */
class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Country $country */
        $country = $this->resource;

        return [
            'code'  => $country->getCode(),
            'name'  => $country->getName(),
            'links' => [
                'states' => route('states.index', ['country' => $country->getCode()], false)
            ]
        ];
    }
}
