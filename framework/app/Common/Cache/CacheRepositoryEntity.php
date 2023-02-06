<?php

namespace App\Common\Cache;

use App\Contracts\CacheStorageInterface;
use App\Models\AbstractModel;
use App\Repositories\CacheableRepositoryInterface;
use App\Repositories\TaxRateRepository;
use Illuminate\Support\Collection;

/**
 * Trait CacheRepositoryEntity
 * @package App\Common\Cache
 */
abstract class CacheRepositoryEntity
{
    /**
     * @var CacheStorageInterface
     */
    protected $cache;

    /**
     * @var string
     */
    protected $repoClass;

    /**
     * @var string
     */
    protected $resourceClass;

    /**
     * @var float
     */
    protected $repoTsValue;

    /**
     * @var float
     */
    protected $entityTsValue;

    /**
     * @var AbstractModel|Collection
     */
    protected $entity;

    /**
     * @var int
     */
    protected $entityId;

    /**
     * @var array
     */
    protected $search;

    /**
     * @var int
     */
    protected $cacheTime;

    /**
     * @param CacheableRepositoryInterface $repository
     *
     * @return CacheRepositoryEntity
     */
    public static function make(CacheableRepositoryInterface $repository)
    {
        return (new static)->setCache($repository->getCache())
            ->setRepoClass(get_class($repository))
            ->setResourceClass(get_class($repository->getResource()))
            ->setRepoTsValue()
            ->setCacheTime($repository->getCacheTime());
    }

    /**
     * @return CacheStorageInterface
     */
    public function getCache(): CacheStorageInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheStorageInterface $cache
     *
     * @return CacheRepositoryEntity
     */
    public function setCache(CacheStorageInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepoClass(): string
    {
        return $this->repoClass;
    }

    /**
     * @param string $repoClass
     *
     * @return CacheRepositoryEntity
     */
    public function setRepoClass(string $repoClass): self
    {
        $this->repoClass = $repoClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    /**
     * @param string $resourceClass
     *
     * @return CacheRepositoryEntity
     */
    public function setResourceClass(string $resourceClass): self
    {
        $this->resourceClass = $resourceClass;

        return $this;
    }

    /**
     * @return float
     */
    public function getRepoTsValue(): float
    {
        return (float)$this->repoTsValue;
    }

    /**
     * @param float $repoTsValue
     *
     * @return CacheRepositoryEntity
     */
    public function setRepoTsValue(float $repoTsValue = null): self
    {
        $this->repoTsValue = $repoTsValue ?: ($this->getCache()->get($this->getRepositoryTsKey()) ?: $this->getTime());

        return $this;
    }

    /**
     * @return float
     */
    public function getEntityTsValue(): float
    {
        return (float)$this->entityTsValue;
    }

    /**
     * @param float|null $entityTsValue
     *
     * @return CacheRepositoryEntity
     */
    public function setEntityTsValue(float $entityTsValue = null): self
    {
        $this->entityTsValue = $entityTsValue ?: ($this->getCache()->get($this->getEntityTsKey()) ?: $this->getTime());

        return $this;
    }

    /**
     * @return AbstractModel|Collection
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param AbstractModel|Collection $entity
     *
     * @return CacheRepositoryEntity
     */
    public function setEntity($entity): self
    {
        $this->entity = $entity;

        if (($this->entity instanceof AbstractModel) && !empty($id = $this->entity->getAttribute($this->entity->getKeyName()))) {
            $this->entity->clearRelations();
            $this->setEntityId($id);
        } else if (empty($this->entityTsValue)) {
            $this->setEntityTsValue();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param string|int $entityId
     *
     * @return CacheRepositoryEntity
     */
    public function setEntityId($entityId): self
    {
        $this->entityId = $entityId;

        if (empty($this->entityTsValue)) {
            $this->setEntityTsValue();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSearch(): array
    {
        return $this->search;
    }

    /**
     * @param array $search
     *
     * @return CacheRepositoryEntity
     */
    public function setSearch(array $search): CacheRepositoryEntity
    {
        $this->search = transformArray($search);

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheTime(): int
    {
        return $this->cacheTime;
    }

    /**
     * @param int $cacheTime
     *
     * @return CacheRepositoryEntity
     */
    public function setCacheTime(int $cacheTime): CacheRepositoryEntity
    {
        $this->cacheTime = $cacheTime;

        return $this;
    }

    /**
     * @param float $timestamp
     *
     * @return bool
     */
    public function isValid(float $timestamp): bool
    {
        return !$this->exists() || $this->getEntityTsValue() < $timestamp;
    }

    /**
     * @return bool
     */
    abstract public function exists(): bool;

    /**
     * @return CacheRepositoryEntity
     */
    abstract public function store(): self;

    /**
     * @return CacheRepositoryEntity
     */
    public function reset(): CacheRepositoryEntity
    {
        $this->cache->forget($this->getEntityTsKey());
        $this->cache->forget($this->getEntityKey());
        $this->entityTsValue = $this->getTime();

        return $this;
    }

    /**
     * @return CacheRepositoryEntity
     */
    protected function saveRepoTsValue(): CacheRepositoryEntity
    {
        $this->cache->add($this->getRepositoryTsKey(), $this->repoTsValue, $this->cacheTime);

        return $this;
    }

    /**
     * @return string
     */
    protected function getRepositoryTsKey(): string
    {
        return $this->key($this->repoClass, 'TS');
    }

    /**
     * @return string
     */
    protected function getResourceKey(): string
    {
        return $this->key($this->repoClass, $this->resourceClass);
    }

    /**
     * @return string
     */
    protected function getEntityKey(): string
    {
        return $this->key(
            $this->getRepositoryTsKey(),
            $this->repoTsValue,
            $this->getEntityTsKey(),
            $this->entityTsValue
        );
    }

    /**
     * @param mixed ...$params
     *
     * @return string
     */
    protected function key(...$params): string
    {
        return implode(':', $params);
    }

    protected function getTime(): float
    {
        return microtime(true);
    }

    /**
     * @return string
     */
    protected abstract function getEntityKeyword(): string;

    /**
     * @return string
     */
    protected abstract function getEntityTsKey(): string;
}