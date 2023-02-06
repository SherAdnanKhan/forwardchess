<?php

namespace App\Models\Cart;

use App\Assets\Location;
use App\Models\Coupon;
use App\Models\Gift;
use App\Models\Product\Product;
use App\Models\TaxRate;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Cart
 * @package App\Models\Cart
 */
class Cart implements Arrayable
{
    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @var Gift[]
     */
    private array $gifts = [];

    /**
     * @var Billing|null
     */
    private ?Billing $billing = null;

    /**
     * @var int
     */
    private int $subTotal = 0;

    /**
     * @var int
     */
    private int $discount = 0;

    /**
     * @var int
     */
    private int $total = 0;

    /**
     * @var int
     */
    private int $tax = 0;

    /**
     * @var Coupon|null
     */
    private ?Coupon $coupon = null;

    /**
     * @var TaxRate|null
     */
    private ?TaxRate $taxRate = null;

    /**
     * @var bool
     */
    private bool $hasUpdates = false;

    /**
     * @var float
     */
    private float $timestamp;

    /**
     * @var Location|null
     */
    private ?Location $location = null;

    /**
     * @param Billing|null $billing
     * @param array        $items
     *
     * @return Cart
     */
    public static function make(Billing $billing = null, array $items = []): Cart
    {
        $instance = new static;
        $instance
            ->setBilling($billing)
            ->setItems($items)
            ->update();

        return $instance;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     *
     * @return Cart
     */
    public function setItems(array $items): self
    {
        foreach ($items as $item) {
            $this->setItem($item);
        }

        return $this;
    }

    /**
     * @param int $id
     *
     * @return Item|null
     */
    public function getItem(int $id): ?Item
    {
        return $this->items[$id] ?? null;
    }

    /**
     * @param Item $item
     * @param bool $overrideExistent
     *
     * @return Cart
     */
    public function setItem(Item $item, bool $overrideExistent = true): self
    {
        $id = $item->getProduct()->id;
        if (!$overrideExistent && isset($this->items[$id])) {
            $item->setQuantity($this->items[$id]->getQuantity() + $item->getQuantity());
        }

        $this->items[$id] = $item->update();
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @param int $id
     *
     * @return Cart
     */
    public function removeItem(int $id): self
    {
        unset($this->items[$id]);
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return Gift[]
     */
    public function getGifts(): array
    {
        return $this->gifts;
    }

    /**
     * @param Gift    $gift
     * @param boolean $sort
     *
     * @return Cart
     */
    public function setGift(Gift $gift, bool $sort = true): self
    {
        $this->gifts[$gift->code] = $gift;
        $this->hasUpdates         = true;

        if ($sort) {
            $this->sortGifts();
        }

        return $this;
    }

    /**
     * @return Cart
     */
    public function sortGifts(): self
    {
        uasort($this->gifts, function ($a, $b) {
            return ($a->amount <=> $b->amount);
        });

        return $this;
    }

    /**
     * @param string $code
     *
     * @return Cart
     */
    public function removeGift(string $code): self
    {
        unset($this->gifts[$code]);
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return Cart
     */
    public function removeGifts(): self
    {
        $this->gifts      = [];
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return int
     */
    public function getSubTotal(): int
    {
        return $this->subTotal;
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * @return Coupon
     */
    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    /**
     * @param Coupon|null $coupon
     *
     * @return Cart
     */
    public function setCoupon(Coupon $coupon = null): Cart
    {
        $this->coupon     = $coupon;
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return TaxRate
     */
    public function getTaxRate(): ?TaxRate
    {
        return $this->taxRate;
    }

    /**
     * @param TaxRate|null $taxRate
     *
     * @return Cart
     */
    public function setTaxRate(TaxRate $taxRate = null): Cart
    {
        $this->taxRate    = $taxRate;
        $this->hasUpdates = true;

        return $this;
    }

    /**
     * @return Billing
     */
    public function getBilling(): ?Billing
    {
        return $this->billing;
    }

    /**
     * @param Billing|null $billing
     *
     * @return Cart
     */
    public function setBilling(Billing $billing = null): Cart
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * @return float
     */
    public function getTimestamp(): float
    {
        return $this->timestamp;
    }

    /**
     * @param float|null $timestamp
     *
     * @return Cart
     */
    public function setTimestamp(float $timestamp = null): Cart
    {
        if (!$timestamp) {
            $timestamp = microtime(true);
        }

        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     *
     * @return Cart
     */
    public function setLocation(Location $location): Cart
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasUpdates(): bool
    {
        return $this->hasUpdates;
    }

    /**
     * @return int
     */
    public function getProductsCounter(): int
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->getProductsCounter();
    }

    /**
     * @param Coupon $coupon
     * @param int    $total
     *
     * @return int
     */
    private function getDiscountValue(Coupon $coupon, int $total): int
    {
        return ($coupon->type == Coupon::TYPE_AMOUNT)
            ? $this->coupon->discount
            : (int)($total * $this->coupon->discount / 100);
    }

    /**
     * @param bool $force
     *
     * @return Cart
     */
    public function update(bool $force = false): Cart
    {
        if ($this->hasUpdates || $force) {
            $productsDiscount    = 0;
            $useProductsDiscount = false;
            $hasProducts         = false;
            $couponProducts      = collect([]);

            if ($hasCoupon = !empty($this->coupon)) {
                $couponProducts      = $this->coupon->getProductsIds();
                $hasProducts         = !$couponProducts->isEmpty();
                $useProductsDiscount = ($this->coupon->type === Coupon::TYPE_PERCENT) && ($hasProducts || $this->coupon->excludeDiscounts);
            }

            $this->subTotal = 0;
            $this->discount = 0;

            if (!empty($this->items)) {
                foreach ($this->items as $item) {
                    $this->subTotal += $item->getTotal();

                    if (!$useProductsDiscount) {
                        continue;
                    }

                    if ($hasProducts && !$couponProducts->has($item->getKey())) {
                        continue;
                    }

                    if (!($this->coupon->excludeDiscounts && $item->getProduct()->hasDiscount())) {
                        $productsDiscount += $this->getDiscountValue($this->coupon, $item->getTotal());
                    }
                }
            } else {
                $this->removeGifts();
            }

            if ($hasCoupon) {
                $this->coupon->discountValue = $useProductsDiscount ? $productsDiscount : $this->getDiscountValue($this->coupon, $this->subTotal);
                $this->discount              += $this->coupon->discountValue;
            }

            foreach ($this->gifts as $gift) {
                $this->discount += $gift->amount;
            }

            $this->discount = min($this->subTotal, $this->discount);
            $this->total    = $this->subTotal - $this->discount;

            if ($this->total === 0) {
                $this->setTaxRate();
            }

            if (!empty($this->taxRate)) {
                $this->tax   = (int)($this->total * $this->taxRate->rate / 100);
                $this->total += $this->tax;
            } else {
                $this->tax = 0;
            }

            $this->hasUpdates = false;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $coupon  = null;
        $taxRate = null;
        $items   = [];
        $gifts   = [];
        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();

            $items[] = [
                'id'        => $item->getKey(),
                'quantity'  => $item->getQuantity(),
                'unitPrice' => (string)toFloatAmount($product->sellPrice),
                'total'     => (string)toFloatAmount($item->getTotal()),
                'product'   => [
                    'sku'       => $product->sku,
                    'publisher' => $product->publisherName,
                    'author'    => $product->author,
                    'title'     => $product->title,
                    'image'     => $product->imageUrl,
                    'url'       => route('site.products.show', $product->url),
                    'isBundle'  => $product->isBundle,
                    'children'  => $product->isBundle ? $this->getBundleChildren($product, $item) : [],
                ]
            ];
        }

        foreach ($this->getGifts() as $gift) {
            $gifts[] = [
                'code'   => $gift->code,
                'amount' => (string)toFloatAmount($gift->amount)
            ];
        }

        if (!empty($this->coupon)) {
            $coupon = [
                'name'     => $this->coupon->name,
                'code'     => $this->coupon->code,
                'discount' => (string)toFloatAmount($this->coupon->discountValue),
            ];
        }

        if (!empty($this->taxRate)) {
            $taxRate = [
                'code' => $this->taxRate->code,
                'name' => $this->taxRate->name,
                'rate' => $this->taxRate->rate
            ];
        }

        return [
            'subTotal' => (string)toFloatAmount($this->subTotal),
            'discount' => (string)toFloatAmount($this->discount),
            'total'    => (string)toFloatAmount($this->total),
            'tax'      => (string)toFloatAmount($this->tax),
            'items'    => $items,
            'gifts'    => $gifts,
            'billing'  => $this->billing ? $this->billing->toArray() : null,
            'coupon'   => $coupon,
            'taxRate'  => $taxRate,
        ];
    }

    private function getBundleChildren(Product $product, Item $item): array
    {
        $children = [];
        if ($product->isBundle) {
            foreach ($product->children()->get() as $child) {
                $children[] = [
                    'id'            => $child->id,
                    'title'         => $child->title,
                    'sellPrice'     => (string)toFloatAmount($item->calculateDiscount($child->sellPrice, $product->hasDiscount() ? $product->discount : 0)),
                    'alreadyBought' => product_bought($child->id)
                ];
            }
        }

        return $children;
    }
}