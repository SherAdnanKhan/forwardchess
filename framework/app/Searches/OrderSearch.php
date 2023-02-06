<?php

namespace App\Searches;

use App\Assets\DateRange;

/**
 * Class OrderSearch
 * @package App\Searches
 */
class OrderSearch extends AbstractSearch
{
    /**
     * @return array
     */
    protected function getRules(): array
    {
        return [
            'userId' => 'string',
            'status' => 'string',
            'period' => 'array',
        ];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function getFilter(string $name, $value)
    {
        return ($name === 'period')
            ? DateRange::make(...$value)
            : $value;
    }
}