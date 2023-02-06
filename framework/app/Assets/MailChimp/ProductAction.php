<?php

namespace App\Assets\MailChimp;

use App\Models\Product\Product;

/**
 * Class ProductAction
 * @package App\Assets
 */
class ProductAction
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var Product
     */
    private Product $product;

    /**
     * @param string  $name
     * @param Product $product
     *
     * @return ProductAction
     */
    public static function make(string $name, Product $product): ProductAction
    {
        return new static($name, $product);
    }

    /**
     * ProductAction constructor.
     *
     * @param string  $name
     * @param Product $product
     */
    private function __construct(string $name, Product $product)
    {
        $this->name    = $name;
        $this->product = $product;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }


}