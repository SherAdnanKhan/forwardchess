<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Traits\ArticleTransformer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ArticleResource
 * @package App\Http\Resources\Blog
 */
class ArticleResource extends JsonResource
{
    use ArticleTransformer;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->getPage($this->resource->refresh(), true);
    }
}
