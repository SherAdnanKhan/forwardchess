<?php

namespace App\Contracts;

use App\Assets\Location;

/**
 * Interface IpLocatorInterface
 * @package App\Contracts
 */
interface IpLocatorInterface
{
    /**
     * @param string $ip
     *
     * @return mixed
     */
    public function get(string $ip): Location;
}