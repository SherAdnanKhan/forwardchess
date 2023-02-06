<?php

namespace App\Builders;

use App\Models\Faq\Faq;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class FaqBuilder
 * @package App\Builders
 */
class FaqBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private $categoriesTable;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @param array $data
     *
     * @return FaqBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var FaqBuilder $instance */
        $instance = app(FaqBuilder::class);

        $mapper = [
            'categoryId'    => 'setCategoryId',
            'active'        => 'setActive',
            'sortBy'        => 'setSortBy',
        ];

        $bulkFields = [];

        foreach ($data as $property => $value) {
            if (is_null($value)) {
                continue;
            }

            if (!array_key_exists($property, $mapper)) {
                $bulkFields[$property] = $value;
                continue;
            }

            call_user_func_array([$instance, $mapper[$property]], [$value]);
        }

        $instance->setBulkFields($bulkFields);

        return $instance;
    }

    /**
     * FaqBuilder constructor.
     *
     * @param Faq $post
     */
    public function __construct(Faq $post)
    {
        $this->resource        = $post;
        $this->mainTable       = $post->getTable();
        $this->categoriesTable = $post->category()->getRelated()->getTable();
    }

    /**
     * @return Builder
     */
    public function init(): Builder
    {
        $this->builder = $this->resource->query();
        $this->builder = $this->builder->with('category');

        $this
            ->addFields()
            ->addJoins()
            ->addWhere()
            ->addSortBy();

        return $this->builder;
    }

    /**
     * @param int $categoryId
     *
     * @return FaqBuilder
     */
    public function setCategoryId(int $categoryId): FaqBuilder
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * @param bool $active
     *
     * @return FaqBuilder
     */
    public function setActive(bool $active): FaqBuilder
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return FaqBuilder
     */
    private function addFields(): FaqBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'categoryId',
            'question',
            'answer',
            'position',
            'active',
            'created_at',
            'updated_at'
        ]);

        $fields[] = DB::raw("{$this->categoriesTable}.`name`" . ' as `categoryName`');

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return FaqBuilder
     */
    private function addJoins(): FaqBuilder
    {
        $this->builder->join($this->categoriesTable, "{$this->mainTable}.categoryId", '=', "{$this->categoriesTable}.id");

        return $this;
    }

    /**
     * @return FaqBuilder
     */
    private function addWhere(): FaqBuilder
    {
        $mappers = [
            'categoryId' => function ($value) {
                return $this->builder->where("{$this->mainTable}.categoryId", '=', $value);
            },

            'active' => function ($value) {
                return $this->builder->where("{$this->mainTable}.active", '=', $value);
            }
        ];

        foreach ($mappers as $property => $value) {
            if (empty($this->{$property})) {
                continue;
            }

            $this->builder = $value($this->{$property});
        }

        $this->addBulkFields();

        return $this;
    }
}