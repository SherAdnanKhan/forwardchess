<?php

namespace App\MailChimp\Repositories;

use App\Models\Product\Product;
use Exception;
use MailchimpMarketing\Api\EcommerceApi;

/**
 * Class ProductRepository
 * @package App\MailChimp\Repositories
 */
class ProductRepository
{
    use ApiCall;

    /**
     * @var EcommerceApi
     */
    private EcommerceApi $gateway;

    /**
     * @var string
     */
    private string $storeId;

    /**
     * ProductRepository constructor.
     *
     * @param EcommerceApi $gateway
     * @param string       $storeId
     */
    public function __construct(EcommerceApi $gateway, string $storeId)
    {
        $this->gateway = $gateway;
        $this->storeId = $storeId;
    }

    /**
     * @param int $page
     * @param int $rowsPerPage
     *
     * @return array|null
     * @throws Exception
     */
    public function all(int $page = 0, int $rowsPerPage = 15): ?array
    {
        $response = $this->makeCall(function () use ($page, $rowsPerPage) {
            return $this->gateway->getAllStoreProducts($this->storeId, null, null, $rowsPerPage, $page * $rowsPerPage);
        });

        $response->handle();

        return $response->getResponse()->products;
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws Exception
     */
    public function get(int $id)
    {
        $response = $this->makeCall(function () use ($id) {
            return $this->gateway->getStoreProduct($this->storeId, $id);
        });

        if (!$response->isSuccess() && ($response->getErrorStatus() !== 404)) {
            $response->handle();
        }

        return $response->isSuccess() ? $response->getResponse() : null;
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws Exception
     */
    public function exists(int $id): bool
    {
        $product = $this->get((string)$id);

        return !is_null($product);
    }

    /**
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function store(Product $product): bool
    {
        $response = $this->makeCall(function () use ($product) {
            return $this->gateway->addStoreProduct($this->storeId, $this->convertProduct($product));
        });

        $response->handle();
        return $response->isSuccess();
    }

    /**
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function update(Product $product): bool
    {
        $response = $this->makeCall(function () use ($product) {
            return $this->gateway->updateStoreProduct($this->storeId, (string)$product->id, $this->convertProduct($product));
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(Product $product): bool
    {
        $exists = $this->exists($product->id);
        if (!$exists) {
            return true;
        }

        $response = $this->makeCall(function () use ($product) {
            $this->gateway->deleteStoreProduct($this->storeId, $product->id);
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param Product $product
     *
     * @return array
     */
    private function convertProduct(Product $product): array
    {
        $productId = (string)$product->id;

        return [
            'id'          => $productId,
            'title'       => $product->title,
            'url'         => route('site.products.show', $product->url),
            'description' => $product->description,
            'image_url'   => $product->imageUrl,
            'variants'    => [
                [
                    'id'         => $productId,
                    'title'      => $product->title,
                    'url'        => route('site.products.show', $product->url),
                    'image_url'  => $product->imageUrl,
                    'sku'        => $product->sku,
                    'price'      => toFloatAmount($product->sellPrice),
                    'visibility' => $product->active ? 'yes' : 'no'
                ]
            ]
        ];
    }
}