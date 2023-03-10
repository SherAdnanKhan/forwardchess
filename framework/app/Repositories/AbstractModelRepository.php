<?php

namespace App\Repositories;

use App\Assets\SortBy;
use App\Common\Cache\CacheModel;
use App\Common\Helpers\ExceptionHelper;
use App\Contracts\CacheStorageInterface;
use App\Exceptions\CommonException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class AbstractModelRepository
 * @package App\Repositories
 *
 * @method AbstractModel|null getByIdOrFail(int $id)
 */
abstract class AbstractModelRepository implements RepositoryInterface, CacheableRepositoryInterface
{
    use ExceptionHelper, CacheRepository;

    const CB_AFTER_STORE   = 'afterStore';
    const CB_AFTER_UPDATE  = 'afterUpdate';
    const CB_AFTER_DESTROY = 'afterDestroy';

    /**
     * @var AbstractModel
     */
    private $resource;

    /**
     * @var bool
     */
    private $trashedIncludedInSearch = false;

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @param AbstractModel         $resource
     * @param CacheStorageInterface $cache
     */
    public function __construct(AbstractModel $resource, CacheStorageInterface $cache)
    {
        $this->setResource($resource);
        $this->setCache($cache);
    }

    /**
     * @return AbstractModel
     */
    public function getResource(): AbstractModel
    {
        return $this->resource;
    }

    /**
     * @param AbstractModel $resource
     *
     * @return RepositoryInterface
     */
    public function setResource(AbstractModel $resource): RepositoryInterface
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTrashedIncludedInSearch(): bool
    {
        return $this->trashedIncludedInSearch;
    }

    /**
     * @param bool $trashedIncludedInSearch
     *
     * @return RepositoryInterface
     */
    public function setTrashedIncludedInSearch(bool $trashedIncludedInSearch): RepositoryInterface
    {
        $this->trashedIncludedInSearch = $trashedIncludedInSearch;

        return $this;
    }

    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Builder
     */
    public function getBuilder(array $search = [], SortBy $sortBy = null): Builder
    {
        $builder = $this->isTrashedIncludedInSearch() ? $this->resource->withTrashed() : $this->resource->query();

        if (!empty($search)) {
            $builder = $builder->where($search);
        }

        if (!empty($sortBy)) {
            $builder = $builder->orderBy($sortBy->getField(), $sortBy->getDirection());
        }

        return $builder;
    }

    /**
     * @param int|string $id
     *
     * @return AbstractModel
     */
    public function getById($id): ?AbstractModel
    {
        $id         = (int)$id;
        $cacheModel = CacheModel::make($this)->setEntityId($id);
        if (!$cacheModel->exists()) {
            $cacheModel->setEntity($this->first(['id' => $id]))->store();
        }

        return $cacheModel->getEntity();
    }

    /**
     * @param array  $search
     * @param SortBy $sortBy
     *
     * @return Collection
     */
    public function get(array $search = [], SortBy $sortBy = null): Collection
    {
        return $this->getBuilder($search, $sortBy)->get();
    }

    /**
     * @param array       $search
     * @param int         $rowsPerPage
     * @param SortBy|null $sortBy
     * @param array       $customParams
     *
     * @return LengthAwarePaginator
     */
    public function paginate(array $search = [], int $rowsPerPage = 15, SortBy $sortBy = null, array $customParams = []): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $collection */
        $collection = $this->getBuilder($search, $sortBy)->paginate($rowsPerPage);

        $customParams = array_merge($this->getPaginateCustomParams($search), $customParams);
        if (!empty($customParams)) {
            $collection->appends($customParams)->links();
        }

        return $collection;
    }

    /**
     * @param array $search
     *
     * @return AbstractModel|null
     */
    public function first(array $search = [])
    {
        /** @var AbstractModel $model */
        $model = $this->getBuilder($search)->first();

        return $model;
    }

    /**
     * @param array|null $data
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function store(array $data = null): AbstractModel
    {
        if (empty($data)) {
            $this->invalidFormDataException();
        }

        $model = $this->getResource()->create($data);
        if (!$model) {
            $this->dataNotSavedException();
        }

        $model = $this->getById($model->id);

        if (method_exists($this, self::CB_AFTER_STORE)) {
            call_user_func_array([$this, self::CB_AFTER_STORE], [$model]);
        }

        return $model;
    }

    /**
     * @param AbstractModel $model
     * @param array|null    $data
     * @param bool          $restore
     *
     * @return AbstractModel
     * @throws CommonException
     */
    public function update(AbstractModel $model, array $data = null, bool $restore = false): AbstractModel
    {
        if (empty($data) && !$restore) {
            $this->invalidFormDataException();
        }

        $attributes = $model->getAttributes();

        foreach ($data as $fieldName => $fieldValue) {
            if (!array_key_exists($fieldName, $attributes)) {
                continue;
            }

            $model->setAttribute($fieldName, $fieldValue);
        }

        $success = ($restore && $model->restore()) || ($model->isDirty() && $model->save()) || !$model->isDirty();
        if (!$success) {
            $this->dataNotSavedException();
        }

        if (method_exists($this, self::CB_AFTER_UPDATE)) {
            call_user_func_array([$this, self::CB_AFTER_UPDATE], [$model]);
        }

        return $model;
    }

    /**
     * @param AbstractModel $model
     *
     * @return bool
     * @throws \Exception
     */
    public function destroy(AbstractModel $model): bool
    {
        $success = $model->delete();
        if (!$success) {
            $this->dataNotSavedException();
        }

        if (method_exists($this, self::CB_AFTER_DESTROY)) {
            call_user_func_array([$this, self::CB_AFTER_DESTROY], [$model]);
        }

        return $success;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed|null
     * @throws CommonException
     * @throws ResourceNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (empty($return = $this->proxyFailFunction($name, $arguments))) {
            throw new CommonException('Method ' . $name . ' not found.');
        } else {
            return $return;
        }
    }

    /**
     * @param array $search
     *
     * @return array
     */
    protected function getPaginateCustomParams(array $search = []): array
    {
        return [];
    }

    /**
     * @param string     $method
     * @param array|null $arguments
     *
     * @return mixed|null
     * @throws ResourceNotFoundException
     */
    private function proxyFailFunction(string $method, array $arguments = null)
    {
        $patternToSearch = '/OrFail/';
        $proxyMethods    = [
            'getById'
        ];

        if (preg_match($patternToSearch, $method)) {
            $findParam = array_shift($arguments);
            if (empty($findParam)) {
                throw new ResourceNotFoundException;
            }

            $method = preg_replace($patternToSearch, '', $method);
            if (!in_array($method, $proxyMethods)) {
                throw new ResourceNotFoundException;
            }

            $response = call_user_func([$this, $method], $findParam);
            if (empty($response)) {
                throw new ResourceNotFoundException;
            }

            return $response;
        }

        return null;
    }
}