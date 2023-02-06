<?php

namespace App\Repositories;

use App\Contracts\CacheStorageInterface;
use Carbon\Carbon;

/**
 * Trait CacheRepository
 * @package App\Common\Cache
 */
trait CacheRepository
{
    /**
     * @var CacheStorageInterface
     */
    protected $cache;

    /**
     * @var int
     */
    protected $cacheTime;

    /**
     * @return CacheStorageInterface
     */
    public function getCache(): CacheStorageInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheStorageInterface $cache
     *
     * @return self
     */
    public function setCache(CacheStorageInterface $cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheTime(): int
    {
        $date = Carbon::now();

        return empty($this->cacheTime) ? $date->addMonth(1)->timestamp : $this->cacheTime;
    }

    /**
     * @param int $cacheTime
     *
     * @return self
     */
    public function setCacheTime(int $cacheTime)
    {
        $this->cacheTime = $cacheTime;

        return $this;
    }
}