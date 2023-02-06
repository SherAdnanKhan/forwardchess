<?php

namespace App\Http\Resources;

use App\Assets\State;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StateResource
 * @package App\Http\Resources\Product
 */
class StateResource extends JsonResource
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
        /** @var State $state */
        $state = $this->resource;

        return [
            'code' => $state->getCode(),
            'name' => $state->getName(),
        ];
    }
}
