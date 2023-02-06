<?php

namespace App\Http\Resources;

use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TaxRateResource
 * @package App\Http\Resources\Product
 */
class TaxRateResource extends JsonResource
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
        /** @var TaxRate $taxRate */
        $taxRate = $this->resource;

        return [
            'code'       => $taxRate->code,
            'country'    => $taxRate->country,
            'rate'       => $taxRate->rate,
            'name'       => $taxRate->name,
            'created_at' => $taxRate->getCreatedAtFormatted(),
            'updated_at' => $taxRate->getUpdatedAtFormatted()
        ];
    }
}
