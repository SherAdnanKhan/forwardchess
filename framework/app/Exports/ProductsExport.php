<?php

namespace App\Exports;

use App\Models\Product\Product;
use App\Repositories\Product\ProductRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductsExport constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this
            ->productRepository
            ->get(['active' => 1])
            ->map(function (Product $product) {
                return [
                    'id'        => $product->id,
                    'title'     => $product->title,
                    'url'       => route('site.products.show', $product->url),
                    'price'     => toFloatAmount($product->sellPrice),
                    'image'     => $product->imageUrl,
                    'publisher' => $product->publisherName

                ];
            });
    }

    public function headings(): array
    {
        return $this->collection()->count() ? array_keys($this->collection()->first()) : [];
    }
}