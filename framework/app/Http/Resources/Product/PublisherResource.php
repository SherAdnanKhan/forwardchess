<?php

namespace App\Http\Resources\Product;

use App\Models\Product\Publisher;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PublisherResource
 * @package App\Http\Resources\Product
 */
class PublisherResource extends JsonResource
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
        /** @var Publisher $publisher */
        $publisher = $this->resource;

        return [
            'id'         => $publisher->id,
            'name'       => $publisher->name,
            'logo'       => $publisher->logoUrl,
            'position'   => $publisher->position,
            'created_at' => $publisher->getCreatedAtFormatted(),
            'updated_at' => $publisher->getUpdatedAtFormatted(),
            'links'      => [
                'products' => route('products.index', ['publisherId' => $publisher->id], false)
            ]
        ];
    }
}
