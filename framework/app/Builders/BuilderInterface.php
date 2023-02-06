<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface BuilderInterface
 * @package App\Builders
 */
interface BuilderInterface
{
    /**
     * @param array $data
     *
     * @return BuilderInterface
     */
    public static function make(array $data = []): BuilderInterface;

    /**
     * @return Builder
     */
    public function init(): Builder;
}