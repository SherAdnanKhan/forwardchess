<?php

namespace App\Repositories;

use App\Assets\SortBy;
use App\Builders\FaqBuilder;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class FaqRepository
 * @package App\Repositories
 */
class FaqRepository extends AbstractModelRepository
{
    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $search['sortBy'] = $sortBy;

        return FaqBuilder::make($search)->init();
    }
}