<?php

namespace App\Assets;

use App\Models\Cart\Item;
use App\Models\Coupon;
use App\Models\Gift;

/**
 * Class OrderToSave
 * @package App\Assets
 */
class PlaceOrder
{
    /**
     * @var array
     */
    private $details;

    /**
     * @var Item[]
     */
    private $items;

    /**
     * @var Gift[]
     */
    private $gifts;

    /**
     * @var array
     */
    private $billing;

    /**
     * @var array
     */
    private $payment;

    /**
     * @var Coupon
     */
    private $coupon;

    /**
     * @var GiftCard
     */
    private $giftCard;

    /**
     * @return array|null
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @param array|null $details
     * @param boolean    $merge
     *
     * @return self
     */
    public function setDetails(array $details = null, $merge = true): self
    {
        if ($merge && is_array($this->details)) {
            $this->details = array_merge($this->details, $details);
        } else {
            $this->details = $details;
        }

        return $this;
    }

    /**
     * @return Item[]|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     *
     * @return self
     */
    public function setItems(array $items = null): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return Gift[]|null
     */
    public function getGifts(): ?array
    {
        return $this->gifts;
    }

    /**
     * @param Gift[] $gifts
     *
     * @return self
     */
    public function setGifts(array $gifts = []): self
    {
        $this->gifts = $gifts;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getBilling(): ?array
    {
        return $this->billing;
    }

    /**
     * @param array|null $billing
     *
     * @return self
     */
    public function setBilling(array $billing = null): self
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPayment(): ?array
    {
        return $this->payment;
    }

    /**
     * @param array|null $payment
     *
     * @return self
     */
    public function setPayment(array $payment = null): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return Coupon|null
     */
    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    /**
     * @param Coupon|null $coupon
     *
     * @return self
     */
    public function setCoupon(Coupon $coupon = null): self
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * @return GiftCard|null
     */
    public function getGiftCard(): ?GiftCard
    {
        return $this->giftCard;
    }

    /**
     * @param GiftCard|null $giftCard
     *
     * @return self
     */
    public function setGiftCard(GiftCard $giftCard = null): self
    {
        $this->giftCard = $giftCard;

        return $this;
    }
}