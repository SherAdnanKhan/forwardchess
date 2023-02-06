<?php

namespace App\Assets\Ecommerce;

use App\Models\Product\Product;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class ProductAsset
 * @package App\Assets\Ecommerce
 */
class ProductAsset implements Arrayable
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $price;

    /**
     * @var string
     */
    private string $brand;

    /**
     * @var string|null
     */
    private ?string $category = null;

    /**
     * @var int
     */
    private int $quantity;

    /**
     * @var string|null
     */
    private ?string $section = null;

    /**
     * @param Product $product
     *
     * @return ProductAsset
     */
    public static function make(Product $product): ProductAsset
    {
        $instance = new static;
        $instance->setId($product->sku)
            ->setName($product->title)
            ->setBrand($product->publisherName)
            ->setPrice($product->sellPrice);

        if ($product->categories()->exists()) {
            $categories = $product->categories->map(function ($category) {
                return $category->name;
            })->implode(', ');
            $instance->setCategory($categories);
        }

        return $instance;
    }

    /**
     * @param string $id
     *
     * @return ProductAsset
     */
    public function setId(string $id): ProductAsset
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return ProductAsset
     */
    public function setName(string $name): ProductAsset
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $price
     *
     * @return ProductAsset
     */
    public function setPrice(string $price): ProductAsset
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param string $brand
     *
     * @return ProductAsset
     */
    public function setBrand(string $brand): ProductAsset
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @param string $category
     *
     * @return ProductAsset
     */
    public function setCategory(string $category): ProductAsset
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param int $quantity
     *
     * @return ProductAsset
     */
    public function setQuantity(int $quantity): ProductAsset
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param string|null $section
     *
     * @return ProductAsset
     */
    public function setSection(?string $section): ProductAsset
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $attributes = [
            'id'       => $this->id,
            'name'     => $this->name,
            'price'    => $this->price,
            'brand'    => $this->brand,
            'category' => $this->category,
            'section'  => $this->section,
        ];

        foreach ($attributes as $property => $value) {
            if (is_null($value)) {
                unset($attributes[$property]);
            }
        }

        return $attributes;
    }
}