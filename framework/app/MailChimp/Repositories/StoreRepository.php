<?php

namespace App\MailChimp\Repositories;

use App\MailChimp\Assets\Store;
use Exception;
use MailchimpMarketing\Api\EcommerceApi;

/**
 * Class StoreRepository
 * @package App\MailChimp\Repositories
 */
class StoreRepository
{
    use ApiCall;

    /**
     * @var EcommerceApi
     */
    private EcommerceApi $gateway;

    /**
     * StoreRepository constructor.
     *
     * @param EcommerceApi $gateway
     */
    public function __construct(EcommerceApi $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function all(): ?array
    {
        $response = $this->makeCall(function () {
            return $this->gateway->stores();
        });

        $response->handle();

        return $response->getResponse()->stores;
    }

    /**
     * @param Store $store
     *
     * @return bool
     * @throws Exception
     */
    public function store(Store $store): bool
    {
        $response = $this->makeCall(function () use ($store) {
            return $this->gateway->addStore($store->toArray());
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param string $storeId
     * @param string $listId
     *
     * @return Store
     */
    private function makeStore(string $storeId, string $listId): Store
    {
        return (new Store())
            ->setId($storeId)
            ->setListId($listId)
            ->setName('ForwardChess store');
    }
}