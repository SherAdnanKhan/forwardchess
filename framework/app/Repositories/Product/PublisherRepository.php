<?php

namespace App\Repositories\Product;

use App\Assets\SortBy;
use App\Common\Cache\CacheCollection;
use App\Repositories\AbstractModelRepository;
use Illuminate\Support\Collection;

/**
 * Class PublisherRepository
 * @package App\Repositories\Product
 */
class PublisherRepository extends AbstractModelRepository
{
    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Collection
     */
    public function get(array $search = [], SortBy $sortBy = null): Collection
    {
        $cacheCollection = CacheCollection::make($this, $search);

        // todo implement protection on a higher level
        if ($cacheCollection->exists()) {
            $list = $cacheCollection->getEntity();
            foreach ($list as $item) {
                if (!is_object($item)) {
                    $cacheCollection->reset();
                    break;
                }
            }
        }

        if (!$cacheCollection->exists()) {
            $cacheCollection->setEntity($this->getBuilder($search, $sortBy)->get())->store();
        }

        return $cacheCollection->getEntity();
    }
}