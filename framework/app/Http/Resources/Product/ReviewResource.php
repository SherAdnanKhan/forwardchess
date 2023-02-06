<?php

namespace App\Http\Resources\Product;

use App\Models\Product\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ReviewResource
 * @package App\Http\Resources\Product
 */
class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     *
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Review $review */
        $review = $this->resource;

        return [
            $this->mergeWhen($request->user() && $request->user()->isAdmin, [
                'id'         => $review->id,
                'userId'     => $review->userId,
                'approved'   => $review->approved,
                'productId'  => $review->productId,
                'updated_at' => $review->getUpdatedAtFormatted()
            ]),

            'productName' => $review->productName,
            'nickname'    => $review->nickname ?: $review->userName,
            'title'       => $review->title,
            'description' => $review->description,
            'rating'      => $review->rating,
            'created_at'  => $review->getCreatedAtFormatted(),
        ];
    }
}
