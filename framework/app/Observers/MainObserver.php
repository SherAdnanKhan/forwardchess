<?php

namespace App\Observers;

use App\Common\Cache\CacheCollection;
use App\Common\Cache\CacheModel;
use App\Exceptions\CommonException;
use App\Models\AbstractModel;
use App\Repositories\CacheableRepositoryInterface;
use App\Repositories\RepositoryInterface;

/**
 * Class MainObserver
 * @package App\Observers
 */
class MainObserver
{
    /**
     * @var array
     */
    protected $observers = [];

    /**
     * MainObserver constructor.
     */
    public function __construct()
    {
        $this->observers = config('observers');
    }

    /**
     * @param AbstractModel $model
     *
     * @return RepositoryInterface|CacheableRepositoryInterface
     * @throws CommonException
     */
    protected function getRepository(AbstractModel $model): RepositoryInterface
    {
        $modelClass = get_class($model);
        if (!array_key_exists($modelClass, $this->observers)) {
            throw new CommonException('No observer found for ' . $modelClass);
        }

        return app($this->observers[$modelClass]);
    }

    /**
     * Listen to the models created event.
     *
     * @param  AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function created(AbstractModel $model): bool
    {
        CacheCollection::make($this->getRepository($model))->resetAllCollection();

        return true;
    }

    /**
     * Listen to the models updating event.
     *
     * @param  AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function updating(AbstractModel $model): bool
    {
        CacheModel::make($this->getRepository($model))->setEntity($model)->reset();

        return true;
    }

    /**
     * Listen to the models deleting event.
     *
     * @param  AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function deleting(AbstractModel $model): bool
    {
        return $this->resetting($model);
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function restored(AbstractModel $model): bool
    {
        return $this->resetting($model);
    }

    /**
     * @param AbstractModel $model
     *
     * @return bool
     * @throws CommonException
     */
    public function resetting(AbstractModel $model)
    {
        CacheModel::make($this->getRepository($model))->setEntity($model)->reset();

        return true;
    }
}