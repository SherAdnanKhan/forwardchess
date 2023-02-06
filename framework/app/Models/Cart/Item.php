<?php

namespace App\Models\Cart;

use App\Models\Product\Product;

/**
 * Class Item
 * @package App\Models\Cart
 */
class Item
{
    /**
     * @var Product
     */
    private Product $product;

    /**
     * @var int
     */
    private int $quantity = 1;

    /**
     * @var string|null
     */
    private ?string $section = '';

    /**
     * @var int
     */
    private int $total = 0;

    /**
     * @var bool
     */
    private bool $hasUpdates = false;

    /**
     * @param Product     $product
     * @param int         $quantity
     * @param string|null $section
     *
     * @return Item
     */
    public static function make(Product $product, int $quantity = 1, ?string $section = ''): Item
    {
        $instance = new static();
        $instance
            ->setProduct($product)
            ->setQuantity($quantity)
            ->setSection($section)
            ->update();

        return $instance;
    }

    /**
     * @return int
     */
    public function getKey(): int
    {
        return $this->getProduct()->id;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return Item
     */
    public function setProduct(Product $product): Item
    {
        $this->product    = $product;
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Item
     */
    public function setQuantity(int $quantity): Item
    {
        $this->quantity   = $quantity;
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getSection(): ?string
    {
        return $this->section;
    }

    /**
     * @param string|null $section
     *
     * @return Item
     */
    public function setSection(?string $section): Item
    {
        $this->section    = $section;
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return Item
     */
    public function setTotal(int $total = 0): Item
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @param bool $force
     *
     * @return Item
     */
    public function update(bool $force = false): self
    {
        if ($this->hasUpdates || $force) {
            $this->total      = $this->quantity * $this->product->sellPrice;
            $this->hasUpdates = false;
        }

        return $this;
    }

    /**
     * @param int $sellPrice
     * @param int $discount
     *
     * @return int
     */
    public function calculateDiscount(int $sellPrice = 0, int $discount = 0): int
    {
        return $discount
            ? ($sellPrice - $sellPrice * $discount / 10000)
            : $sellPrice;
    }
}