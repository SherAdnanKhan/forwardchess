<?php

namespace App\Http\Resources;

use App\Models\Gift;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class GiftResource
 * @package App\Http\Resources\Product
 */
class GiftResource extends JsonResource
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
        /** @var Gift $gift */
        $gift = $this->resource;

        return [
            'id'            => $gift->id,
            'code'          => $gift->code,
            'amount'        => (string)toFloatAmount($gift->amount),
            'friendEmail'   => $gift->friendEmail,
            'friendName'    => $gift->friendName,
            'friendMessage' => $gift->friendMessage,

            $this->mergeWhen($request->user() && $request->user()->isAdmin, [
                'userId'         => $gift->userId,
                'buyer'          => $gift->buyer,
                'orderId'        => $gift->orderId,
                'originalAmount' => (string)toFloatAmount($gift->amount),
                'expireDate'     => $gift->getExpireDateFormatted(),
                'enabled'        => $gift->enabled,
            ]),

            'created_at' => $gift->getCreatedAtFormatted(),
            'updated_at' => $gift->getUpdatedAtFormatted(),
        ];
    }
}
