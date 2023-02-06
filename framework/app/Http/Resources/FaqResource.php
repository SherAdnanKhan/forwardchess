<?php

namespace App\Http\Resources;

use App\Models\Faq\Faq;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FaqResource
 * @package App\Http\Resources\Product
 */
class FaqResource extends JsonResource
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
        /** @var Faq $post */
        $post = $this->resource;

        return [
            'id'           => $post->id,
            'categoryId'   => $post->categoryId,
            'categoryName' => $post->categoryName,
            'question'     => $post->question,
            'answer'       => $post->answer,
            'position'     => $post->position,
            'active'       => $post->active,
            'created_at'   => $post->getCreatedAtFormatted(),
            'updated_at'   => $post->getUpdatedAtFormatted()
        ];
    }
}
