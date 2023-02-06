<?php

namespace App\Http\Resources;

use App\Models\Newsletter;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NewsletterResource
 * @package App\Http\Resources\Product
 */
class NewsletterResource extends JsonResource
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
        /** @var Newsletter $post */
        $post = $this->resource;

        return [
            'id'         => $post->id,
            'email'      => $post->email,
            'created_at' => $post->getCreatedAtFormatted(),
            'updated_at' => $post->getUpdatedAtFormatted(),
        ];
    }
}
