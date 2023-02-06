<?php

namespace App\Contracts;

/**
 * Interface CacheStorageInterface
 * @package App\Contracts
 */
interface CacheStorageInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);


    /**
     * @param string $key
     * @param $value
     * @param $minutes
     *
     * @return mixed
     */
    public function add(string $key, $value, $minutes);

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $minutes
     *
     * @return mixed
     */
    public function put(string $key, $value, $minutes);

    /**
     * @param string $key
     * @param mixed $minutes
     * @param \Closure $closure
     *
     * @return mixed
     */
    public function remember(string $key, $minutes, \Closure $closure);

    /**
     * @param string $key
     * @param \Closure $closure
     *
     * @return mixed
     */
    public function rememberForever(string $key, \Closure $closure);

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function forever(string $key, $value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function forget(string $key);
}