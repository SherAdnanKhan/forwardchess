<?php

namespace App\Repositories;

use App\Contracts\CacheStorageInterface;
use App\Models\AbstractModel;

/**
 * Interface CacheableRepositoryInterface
 * @package App\Contracts
 */
interface CacheableRepositoryInterface
{
    /**
     * gets the repository resource for all the queries
     *
     * @return AbstractModel
     */
    public function getResource(): AbstractModel;

    /**
     * gets the cache handler
     *
     * @return CacheStorageInterface
     */
    public function getCache(): CacheStorageInterface;

    /**
     * sets the cache handler
     *
     * @param CacheStorageInterface $cacheStorage
     *
     * @return mixed
     */
    public function setCache(CacheStorageInterface $cacheStorage);

    /**
     * gets the cache time
     *
     * @return int
     */
    public function getCacheTime(): int;

    /**
     * sets the cache time
     *
     * @param int $cacheTime
     *
     * @return mixed
     */
    public function setCacheTime(int $cacheTime);
}