<?php

namespace App\Http\Resources\Traits;

use App\Models\Product\Product;

trait ProductTransformer
{
    protected function getProduct(Product $product, bool $fullMode = false): array
    {
        $price = $fullMode ? $product->price : $product->sellPrice;

        $data = [
            'id'                => $product->id,
            'publisherId'       => $product->publisherId,
            'publisher'         => $product->publisherName,
            'author'            => $product->author,
            'title'             => $product->title,
            'sku'               => $product->sku,
            'image'             => $product->imageUrl,
            'level'             => $product->level,
            'price'             => (string)toFloatAmount($price),
            'discountType'      => $product->discountType,
            'discount'          => (string)toFloatAmount($product->discount),
            'discountStartDate' => $product->formatDate($product->discountStartDate),
            'discountEndDate'   => $product->formatDate($product->discountEndDate),
        ];


        if ($fullMode) {
            $categories = $product->categories->map(function ($category) {
                return $category->id;
            })->unique();

            $productBundles = [];
            if($product->isBundle) {
                // fix this
                $productBundles = $product->children->map(function ($productBundle) {
                    return $productBundle;
                });
            }

            $data = array_merge($data, [
                'categories'  => $categories,
                'author'      => $product->author,
                'description' => $product->description,
                'nrPages'     => $product->nrPages,
                'totalSales'  => $product->totalSales,
                'publishDate' => $product->publishDate,
                'active'      => $product->active,
                'position'    => $product->position,
                'sellPrice'   => (string)toFloatAmount($product->sellPrice),
                'url'         => route('site.products.show', $product->url),
                'isBundle'    => $product->isBundle,
                'bundleProducts'    => count($productBundles) > 0 ? $productBundles->toArray() : [],
            ]);
        }

        $data = array_merge($data, [
            'created_at' => $product->getCreatedAtFormatted(),
            'updated_at' => $product->getUpdatedAtFormatted(),
            'deleted_at' => $product->getDeletedAtFormatted(),

            'links' => [
                'publisher' => route('publishers.show', ['publisher' => $product->publisherId], false)
            ]
        ]);

        return $data;
    }

    // fix this
    protected function implodeArr(array $arr)
    {
        return implode(',',$arr);
    }
}