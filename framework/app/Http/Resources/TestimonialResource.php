<?php

namespace App\Http\Resources;

use App\Models\Testimonial;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TestimonialResource
 * @package App\Http\Resources\Product
 */
class TestimonialResource extends JsonResource
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
        /** @var Testimonial $post */
        $post = $this->resource;

        return [
            'id'          => $post->id,
            'user'        => $post->user,
            'description' => $post->description,
            'video'       => $post->video,
            'active'      => $post->active,
            'created_at'  => $post->getCreatedAtFormatted(),
            'updated_at'  => $post->getUpdatedAtFormatted()
        ];
    }
}
