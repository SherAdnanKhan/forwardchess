<?php

namespace App\Repositories;

use App\Common\Cache\CacheModel;
use App\Models\AbstractModel;

/**
 * Class TaxRateRepository
 * @package App\Repositories
 */
class TaxRateRepository extends AbstractModelRepository
{
    /**
     * @param int|string $id
     *
     * @return AbstractModel
     */
    public function getById($id): ?AbstractModel
    {
        $cacheModel = CacheModel::make($this)->setEntityId($id);
        if (!$cacheModel->exists()) {
            $cacheModel->setEntity($this->first(['code' => $id]))->store();
        }

        return $cacheModel->getEntity();
    }
}