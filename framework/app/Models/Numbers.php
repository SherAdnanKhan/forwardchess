<?php

namespace App\Models;

/**
 * Trait Numbers
 * @package App\Models
 */
trait Numbers
{
    /**
     * @param float $number
     *
     * @return int
     */
    public function toIntAmount(float $number = null): int
    {
        return is_null($number) ? 0 : toIntAmount($number);
    }

    /**
     * @param int $number
     *
     * @return float
     */
    public function toFloatAmount(int $number = null): float
    {
        return is_null($number) ? 0 : toFloatAmount($number);
    }
}