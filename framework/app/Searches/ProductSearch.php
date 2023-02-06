<?php

namespace App\Searches;

/**
 * Class ProductSearch
 * @package App\Searches
 */
class ProductSearch extends AbstractSearch
{
    /**
     * @return array
     */
    protected function getRules(): array
    {
        return [
            'publisherId'   => 'string',
            'categoryId'    => 'string',
            'trashIncluded' => 'boolean',
        ];
    }
}