<?php

namespace App\Builders;

use App\Models\Product\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ReviewBuilder
 * @package App\Builders
 */
class ReviewBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private string $usersTable;

    /**
     * @var string
     */
    private string $productsTable;

    /**
     * @var int
     */
    private int $productId;

    /**
     * @var bool
     */
    private bool $approved;

    /**
     * @param array $data
     *
     * @return ReviewBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var ReviewBuilder $instance */
        $instance = app(ReviewBuilder::class);

        $mapper = [
            'productId' => 'setProductId',
            'approved'  => 'setApproved',
            'sortBy'    => 'setSortBy',
        ];

        $instance->setBulkFields($instance->processBulkFields($data, $mapper));

        return $instance;
    }

    /**
     * ReviewBuilder constructor.
     *
     * @param Review $review
     */
    public function __construct(Review $review)
    {
        $this->resource      = $review;
        $this->mainTable     = $review->getTable();
        $this->usersTable    = $review->user()->getRelated()->getTable();
        $this->productsTable = $review->product()->getRelated()->getTable();
    }

    /**
     * @return Builder
     */
    public function init(): Builder
    {
        $this->builder = $this->resource->query();
        $this->builder = $this->builder->with('user');

        $this
            ->addFields()
            ->addJoins()
            ->addWhere()
            ->addSortBy();

        return $this->builder;
    }

    /**
     * @param int $productId
     *
     * @return ReviewBuilder
     */
    public function setProductId(int $productId): ReviewBuilder
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @param bool $approved
     */
    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }

    /**
     * @return self
     */
    protected function addSortBy(): self
    {
        if (!empty($this->sortBy)) {
            $this->builder = $this->builder->orderBy("{$this->mainTable}.{$this->sortBy->getField()}", $this->sortBy->getDirection());
        }

        return $this;
    }

    /**
     * @return ReviewBuilder
     */
    private function addFields(): ReviewBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'userId',
            'productId',
            'title',
            'description',
            'rating',
            'approved',
            'created_at',
            'updated_at',
        ]);

        $fields[] = DB::raw("CONCAT_WS(' ', `{$this->usersTable}`.`firstName`, `{$this->usersTable}`.`lastName`) as `userName`");
        $fields[] = DB::raw("`{$this->usersTable}`.`nickname`" . ' as `nickname`');
        $fields[] = DB::raw("`{$this->productsTable}`.`title`" . ' as `productName`');

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return ReviewBuilder
     */
    private function addJoins(): ReviewBuilder
    {
        $this->builder->join($this->usersTable, "{$this->mainTable}.userId", '=', "{$this->usersTable}.id");
        $this->builder->join($this->productsTable, "{$this->mainTable}.productId", '=', "{$this->productsTable}.id");

        return $this;
    }

    /**
     * @return ReviewBuilder
     */
    private function addWhere(): ReviewBuilder
    {
        $mappers = [
            'userId' => function ($value) {
                return $this->builder->where("{$this->mainTable}.userId", '=', $value);
            },

            'productId' => function ($value) {
                return $this->builder->where("{$this->mainTable}.productId", '=', $value);
            },

            'approved' => function ($value) {
                return $this->builder->where("{$this->mainTable}.approved", '=', $value ? 1 : 0);
            },
        ];

        foreach ($mappers as $property => $value) {
            if (!isset($this->{$property}) || is_null($this->{$property})) {
                continue;
            }

            $this->builder = $value($this->{$property});
        }

        $this->addBulkFields();

        return $this;
    }
}