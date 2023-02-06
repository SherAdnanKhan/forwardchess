<?php

namespace App\Common\Cache;

use App\Exceptions\CommonException;

/**
 * Class CacheResource
 * @package App\Common\Cache
 */
class CacheModel extends CacheRepositoryEntity
{
    /**
     * @return bool
     */
    public function exists(): bool
    {
        $model = $this->cache->get($this->getEntityKey());
        if (!empty($model)) {
            $this->setEntity($model);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return CacheRepositoryEntity
     * @throws CommonException
     */
    public function store(): CacheRepositoryEntity
    {
        if (!empty($model = $this->getEntity())) {
            $cacheTime = $this->getCacheTime();

            $model->syncOriginal();
            $this->cache->put($this->getEntityKey(), $model, $cacheTime);
            $this->cache->put($this->getEntityTsKey(), $this->entityTsValue, $cacheTime);

            $this->saveRepoTsValue();
        }

        return $this;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function getIdKey(int $id): string
    {
        return $this->key($this->getResourceKey(), 'ID', $id);
    }

    /**
     * @return string
     */
    protected function getEntityKeyword(): string
    {
        return 'MODEL';
    }

    /**
     * @return string
     * @throws CommonException
     */
    protected function getEntityTsKey(): string
    {
        if (empty($this->entityId)) {
            throw new CommonException('Invalid entity id');
        }

        return $this->key(
            $this->getResourceClass(),
            $this->getEntityKeyword(),
            $this->getEntityId(),
            'TS');
    }
}