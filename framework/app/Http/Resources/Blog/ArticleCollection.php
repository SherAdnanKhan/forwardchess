<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Traits\ArticleTransformer;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class ArticleCollection
 * @package App\Http\Resources\Blog
 */
class ArticleCollection extends ResourceCollection
{
    use ArticleTransformer;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($page) {
            return $this->getPage($page);
        })->toArray();
    }
}
