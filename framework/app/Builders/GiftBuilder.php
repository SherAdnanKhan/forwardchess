<?php

namespace App\Builders;

use App\Models\Gift;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class GiftBuilder
 * @package App\Builders
 */
class GiftBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private $usersTable;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $checkDate;

    /**
     * @param array $data
     *
     * @return GiftBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var GiftBuilder $instance */
        $instance = app(GiftBuilder::class);

        $mapper = [
            'code'      => 'setCode',
            'checkDate' => 'setCheckDate',
            'sortBy'    => 'setSortBy',
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
     * GiftBuilder constructor.
     *
     * @param Gift $gift
     */
    public function __construct(Gift $gift)
    {
        $this->resource   = $gift;
        $this->mainTable  = $gift->getTable();
        $this->usersTable = $gift->user()->getRelated()->getTable();
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
     * @param string|array $code
     *
     * @return GiftBuilder
     */
    public function setCode($code): GiftBuilder
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string $checkDate
     */
    public function setCheckDate(string $checkDate): void
    {
        $this->checkDate = $checkDate;
    }

    /**
     * @return GiftBuilder
     */
    private function addFields(): GiftBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'userId',
            'code',
            'amount',
            'originalAmount',
            'friendEmail',
            'friendName',
            'friendMessage',
            'orderId',
            'enabled',
            'expireDate',
            'created_at',
            'updated_at'
        ]);

        $fields[] = DB::raw("CONCAT_WS(' ', `{$this->usersTable}`.`firstName`, `{$this->usersTable}`.`lastName`) as `buyer`");

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return GiftBuilder
     */
    private function addJoins(): GiftBuilder
    {
        $this->builder->join($this->usersTable, "{$this->mainTable}.userId", '=', "{$this->usersTable}.id");

        return $this;
    }

    /**
     * @return GiftBuilder
     */
    private function addWhere(): GiftBuilder
    {
        $mappers = [
            'code' => function ($value) {
                return is_array($value)
                    ? $this->builder->whereIn("{$this->mainTable}.code", $value)
                    : $this->builder->where("{$this->mainTable}.code", '=', $value);
            },

            'checkDate' => function ($value) {
                return $this->builder
                    ->where("{$this->mainTable}.expireDate", '>', $value)
                    ->where("{$this->mainTable}.enabled", '=', 1)
                    ->where("{$this->mainTable}.amount", '>', 0);
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