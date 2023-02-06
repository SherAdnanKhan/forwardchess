<?php

namespace App\Assets\MailChimp;

use App\Models\Product\Product;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class AbandonedProduct
 * @package App\Assets
 */
class AbandonedProduct implements Arrayable
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $image;

    /**
     * @var string
     */
    private string $url;

    /**
     * @param Product $product
     *
     * @return AbandonedProduct
     */
    public static function make(Product $product): AbandonedProduct
    {
        $instance = new static();
        return $instance
            ->setId((string)$product->id)
            ->setTitle($product->title)
            ->setImage($product->imageUrl)
            ->setUrl(route('site.products.show', $product->url));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return AbandonedProduct
     */
    public function setId(string $id): AbandonedProduct
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return AbandonedProduct
     */
    public function setTitle(string $title): AbandonedProduct
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return AbandonedProduct
     */
    public function setImage(string $image): AbandonedProduct
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return AbandonedProduct
     */
    public function setUrl(string $url): AbandonedProduct
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'BA_PID'    => $this->getId(),
            'BA_PTITLE' => $this->getTitle(),
            'BA_PIMAGE' => $this->getImage(),
            'BA_PURL'   => $this->getUrl(),
        ];
    }
}