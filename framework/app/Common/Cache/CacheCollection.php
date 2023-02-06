<?php

namespace App\Common\Cache;

use App\Models\AbstractModel;
use App\Repositories\CacheableRepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Class CacheCollection
 * @package App\Common\Cache
 */
class CacheCollection extends CacheRepositoryEntity
{
    /**
     * @param CacheableRepositoryInterface $repository
     * @param array|null                   $search
     *
     * @return CacheCollection
     */
    public static function make(CacheableRepositoryInterface $repository, array $search = null): CacheCollection
    {
        /** @var CacheCollection $instance */
        $instance = parent::make($repository);

        if (!empty($search)) {
            $instance->setSearch($search);
        }

        $instance->setEntityTsValue();

        return $instance;
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        $exists = false;
        $list   = $this->cache->get($this->getEntityKey());
        if (!empty($list)) {
            $collection = $this->getModels($list);
            if ($collection->count()) {
                $this->setEntity($collection);
                $exists = true;
            }
        }

        return $exists;
    }

    /**
     * @return CacheRepositoryEntity
     */
    public function store(): CacheRepositoryEntity
    {
        if (!empty($collection = $this->getEntity())) {
            $cacheTime   = $this->getCacheTime();
            $entityTsKey = $this->getEntityTsKey();
            $entityKey   = $this->getEntityKey();
            $modelsIds   = [];

            foreach ($collection as $model) {
                $cacheModel = $this->makeCacheModel($model);
                if (!$cacheModel->exists()) {
                    $cacheModel->setEntityTsValue($this->getEntityTsValue())->store();
                }

                $modelsIds[] = $cacheModel->getEntityId();
            }

            $this->cache->put($entityTsKey, $this->entityTsValue, $cacheTime);
            $this->cache->put($entityKey, $modelsIds, $cacheTime);

            $this->saveRepoTsValue();

            $this->addCollectionToCacheList($entityTsKey, $entityKey);
        }

        return $this;
    }

    /**
     * @return CacheCollection
     */
    public function resetAllCollection(): CacheCollection
    {
        $collectionsListKey = $this->getCollectionsListKey();
        $collectionsList    = $this->cache->get($collectionsListKey) ?: [];

        if (!empty($collectionsList)) {
            foreach ($collectionsList as $item) {
                $this->cache->forget($item['entityTsKey']);
                $this->cache->forget($item['entityKey']);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getEntityKeyword(): string
    {
        return 'COLLECTION';
    }

    /**
     * @return string
     */
    protected function getEntityTsKey(): string
    {
        return $this->key(
            $this->getResourceClass(),
            $this->getEntityKeyword(),
            empty($this->search) ? 'ALL' : serialize($this->search),
            'TS'
        );
    }

    /**
     * @param string $entityTsKey
     * @param string $entityKey
     *
     * @return CacheCollection
     */
    private function addCollectionToCacheList(string $entityTsKey, string $entityKey): CacheCollection
    {
        $collectionsListKey = $this->getCollectionsListKey();
        $collectionsList    = $this->cache->get($collectionsListKey) ?: [];

        $collectionsList[] = [
            'entityTsKey' => $entityTsKey,
            'entityKey'   => $entityKey
        ];

        $this->cache->forever($collectionsListKey, $collectionsList);

        return $this;
    }

    /**
     * @return string
     */
    private function getCollectionsListKey(): string
    {
        return $this->key($this->getResourceKey(), 'COLLECTIONS');
    }

    /**
     * @param AbstractModel $model
     * @param int           $modelId
     *
     * @return CacheRepositoryEntity
     */
    private function makeCacheModel(AbstractModel $model = null, int $modelId = null): CacheRepositoryEntity
    {
        $cacheModel = (new CacheModel)
            ->setCache($this->getCache())
            ->setRepoClass($this->getRepoClass())
            ->setResourceClass($this->getResourceClass())
            ->setRepoTsValue($this->getRepoTsValue())
            ->setCacheTime($this->getCacheTime());

        if (!empty($model)) {
            $cacheModel->setEntity($model);
        } elseif (!empty($modelId)) {
            $cacheModel->setEntityId($modelId);
        }

        return $cacheModel;
    }

    /**
     * @param array $list
     *
     * @return Collection
     */
    private function getModels(array $list): Collection
    {
        $isValid    = true;
        $collection = collect([]);
        foreach ($list as $key => $modelId) {
            $cacheModel = $this->makeCacheModel(null, $modelId);
            if (!$cacheModel->isValid($this->getEntityTsValue())) {
                $isValid = false;
                break;
            } else {
                $collection->push($cacheModel->getEntity());
            }
        }


        if (!$isValid) {
            $this->reset();
        }

        return $collection;
    }
}