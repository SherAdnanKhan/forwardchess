<?php

namespace App\Common\Cache;

use App\Contracts\CacheStorageInterface;
use Illuminate\Support\Facades\Cache as Driver;

/**
 * Class CacheStorage
 * @package App\Common
 */
class CacheStorage implements CacheStorageInterface
{
    use MemoryStorage;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $key = $this->getCacheKey($key);

        if (empty($value = $this->getMemoryValue($key))) {
            $value = Driver::get($key);

            if (!empty($value)) {
                $this->storeMemoryValue($key, $value, 1);
            }
        }

        return $value;
    }

    /**
     * @param string $key
     * @param $value
     * @param $minutes
     *
     * @return mixed
     */
    public function add(string $key, $value, $minutes)
    {
        $key = $this->getCacheKey($key);

        if (empty($this->getMemoryValue($key))) {
            $this->storeMemoryValue($key, $value, 60 * 60 * 24);
        }

        return Driver::add($key, $value, $minutes);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $minutes
     *
     * @return mixed
     */
    public function put(string $key, $value, $minutes)
    {
        $key = $this->getCacheKey($key);

        $this->storeMemoryValue($key, $value, $minutes);

        return Driver::put($key, $value, $minutes);
    }

    /**
     * @param string $key
     * @param int $minutes
     * @param \Closure $closure
     *
     * @return mixed
     */
    public function remember(string $key, $minutes, \Closure $closure)
    {
        $key = $this->getCacheKey($key);

        return Driver::remember($key, $minutes, $closure);
    }

    /**
     * @param string $key
     * @param \Closure $closure
     *
     * @return mixed
     */
    public function rememberForever(string $key, \Closure $closure)
    {
        $key = $this->getCacheKey($key);

        return Driver::rememberForever($key, $closure);
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function forever(string $key, $value)
    {
        $key = $this->getCacheKey($key);

        $this->storeMemoryValue($key, $value, 60 * 60 * 24);

        return Driver::forever($key, $value);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function forget(string $key)
    {
        $key = $this->getCacheKey($key);

        $this->forgetMemoryValue($key);

        return Driver::forget($key);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return forward_static_call_array([Driver::class, $name], $arguments);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getCacheKey(string $key)
    {
        $cacheKey = env('CACHE_KEYS_PREFIX') . ":" . $key;

        return env('CACHE_KEYS_ENCRYPT', true) ? md5($cacheKey) : $cacheKey;
    }
}