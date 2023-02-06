<?php

namespace App\Http\Resources\Product;

use App\Models\Product\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CategoryResource
 * @package App\Http\Resources\Product
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Category $category */
        $category = $this->resource;

        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'position'   => $category->position,
            'created_at' => $category->getCreatedAtFormatted(),
            'updated_at' => $category->getUpdatedAtFormatted(),
            'links'      => [
                'products' => route('products.index', ['category' => $category->id], false)
            ]
        ];
    }
}
