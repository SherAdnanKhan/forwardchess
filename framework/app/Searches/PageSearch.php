<?php

namespace App\Searches;

/**
 * Class PageSearch
 * @package App\Searches
 */
class PageSearch extends AbstractSearch
{
    /**
     * @return array
     */
    protected function getRules(): array
    {
        return [
            'pageId'        => 'string',
            'tagId'         => 'string',
            'trashIncluded' => 'boolean',
        ];
    }
}