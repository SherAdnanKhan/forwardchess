<?php

namespace App\Http\Resources\Blog;

use App\Models\Blog\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagResource
 * @package App\Http\Resources\Article
 */
class TagResource extends JsonResource
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
        /** @var Tag $tag */
        $tag = $this->resource;

        return [
            'id'         => $tag->id,
            'name'       => $tag->name,
            'created_at' => $tag->getCreatedAtFormatted(),
            'updated_at' => $tag->getUpdatedAtFormatted(),
            'links'      => [
                'page' => route('articles.index', ['tag' => $tag->id], false)
            ]
        ];
    }
}
