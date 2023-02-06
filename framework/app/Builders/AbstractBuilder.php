<?php

namespace App\Builders;

use App\Assets\SortBy;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class AbstractBuilder
 * @package App\Builders
 */
class AbstractBuilder
{
    /**
     * @var AbstractModel
     */
    protected $resource;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $mainTable;

    /**
     * @var bool
     */
    protected $isTrashIncluded = false;

    /**
     * @var array
     */
    protected $bulkFields = [];

    /**
     * @var SortBy
     */
    protected $sortBy;

    /**
     * @param bool $isTrashIncluded
     *
     * @return self
     */
    public function setIsTrashIncluded(bool $isTrashIncluded): self
    {
        $this->isTrashIncluded = $isTrashIncluded;

        return $this;
    }

    /**
     * @param SortBy $sortBy
     *
     * @return self
     */
    public function setSortBy(SortBy $sortBy): self
    {
        $this->sortBy = $sortBy;

        return $this;
    }

    /**
     * @param array $data
     * @param array $mapper
     *
     * @return array
     */
    public function processBulkFields(array $data, array $mapper)
    {
        $fields = [];

        foreach ($data as $property => $value) {
            if (is_null($value)) {
                continue;
            }

            if (!array_key_exists($property, $mapper)) {
                $fields[$property] = $value;
                continue;
            }

            call_user_func_array([$this, $mapper[$property]], [$value]);
        }

        return $fields;
    }

    /**
     * @param array $fields
     *
     * @return self
     */
    public function setBulkFields(array $fields): self
    {
        $this->bulkFields = $fields;

        return $this;
    }

    /**
     * @return self
     */
    protected function addSortBy(): self
    {
        if (!empty($this->sortBy)) {
            $this->builder = $this->builder->orderBy($this->sortBy->getField(), $this->sortBy->getDirection());
        }

        return $this;
    }

    /**
     * @return self
     */
    protected function addBulkFields(): self
    {
        if (!empty($this->bulkFields)) {
            $bulkFields = [];
            foreach ($this->bulkFields as $name => $value) {
                $bulkFields["{$this->mainTable}.{$name}"] = $value;
            }

            $this->builder = $this->builder->where($bulkFields);
        }

        return $this;
    }
}