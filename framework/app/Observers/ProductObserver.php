<?php

namespace App\Observers;

use App\Common\CrudActions;
use App\Exceptions\CommonException;
use App\Jobs\Mailchimp\ProductSyncJob;
use App\Models\AbstractModel;
use App\Models\Product\Product;

/**
 * Class ProductObserver
 * @package App\Observers
 */
class ProductObserver extends MainObserver
{
    /**
     * Listen to the models created event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function created(AbstractModel $model): bool
    {
        /** @var Product $model */

        if (isProduction()) {
            if ($model->active) {
                ProductSyncJob::dispatch($model);
            }
        }

        if ($model->isBundle) {
            $model->calculateBundlePrice();
        }

        return parent::created($model);
    }

    /**
     * Listen to the models updating event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function updating(AbstractModel $model): bool
    {
        /** @var Product $model */
        if (isProduction()) {
            ProductSyncJob::dispatch($model, $model->active ? CrudActions::ACTION_UPDATED : CrudActions::ACTION_REMOVED);
        }

        return parent::updating($model);
    }

    public function updated(AbstractModel $model): bool
    {
        /** @var Product $model */
        if (!$model->isBundle) {
            $dirty = $model->getDirty();

            if (isset($dirty['price']) || isset($dirty['discountType']) || isset($dirty['discount'])) {
                $model->parents->each(function (Product $parent) {
                    $parent->calculateBundlePrice();
                    $parent->save();
                });
            }
        }

        return true;
    }

    /**
     * Listen to the models updating categories event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function changed(AbstractModel $model): bool
    {
        return parent::updating($model);
    }

    /**
     * Listen to the models deleting event.
     *
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function deleting(AbstractModel $model): bool
    {
        if (isProduction()) {
            ProductSyncJob::dispatch($model, CrudActions::ACTION_REMOVED);
        }

        return parent::resetting($model);
    }
}
