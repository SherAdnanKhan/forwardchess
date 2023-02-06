<?php

namespace App\Builders;

use App\Assets\DateRange;
use App\Assets\PriceRange;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderBuilder
 * @package App\Builders
 */
class OrderBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    private $billingTable;

    /**
     * @var array
     */
    private $userId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var PriceRange
     */
    private $dateRange;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @param array $data
     *
     * @return OrderBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var OrderBuilder $instance */
        $instance = app(OrderBuilder::class);

        $mapper = [
            'userId'        => 'setUserId',
            'period'        => 'setDateRange',
            'status'        => 'setStatus',
            'fullName'      => 'setFullName',
            'trashIncluded' => 'setIsTrashIncluded',
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
     * OrderBuilder constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->resource     = $order;
        $this->mainTable    = $order->getTable();
        $this->billingTable = $order->billing()->getRelated()->getTable();
    }

    /**
     * @return Builder
     */
    public function init(): Builder
    {
        $this->builder = $this->isTrashIncluded ? $this->resource->withTrashed() : $this->resource->query();
//        $this->builder = $this->builder->with('billing');

        $this
            ->addFields()
            ->addJoins()
            ->addWhere()
            ->addSortBy();

        return $this->builder;
    }

    /**
     * @param string $userId
     *
     * @return OrderBuilder
     */
    public function setUserId(string $userId): OrderBuilder
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param string $status
     *
     * @return OrderBuilder
     */
    public function setStatus(string $status): OrderBuilder
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param DateRange $dateRange
     *
     * @return OrderBuilder
     */
    public function setDateRange(DateRange $dateRange): OrderBuilder
    {
        $this->dateRange = $dateRange;

        return $this;
    }

    /**
     * @param string $fullName
     *
     * @return OrderBuilder
     */
    public function setFullName(string $fullName): OrderBuilder
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return OrderBuilder
     */
    private function addFields(): OrderBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'userId',
            'status',
            'paymentMethod',
            'currency',
            'subTotal',
            'coupon',
            'discount',
            'taxRateCountry',
            'taxRateAmount',
            'taxAmount',
            'total',
            'refNo',
            'allowDownload',
            'completedDate',
            'paidDate',
            'ipAddress',
            'userAgent',
            'created_at',
            'updated_at'
        ]);

        $fields = array_merge($fields, array_map(function ($field) {
            return "{$this->billingTable}.{$field}";
        }, [
            'email',
        ]));

        $fields[] = DB::raw($this->getFullNameField() . ' as `fullName`');

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return OrderBuilder
     */
    private function addJoins(): OrderBuilder
    {
        $this->builder = $this->builder->join($this->billingTable, 'orders.id', '=', "{$this->billingTable}.orderId");

        return $this;
    }

    /**
     * @return OrderBuilder
     */
    private function addWhere(): OrderBuilder
    {
        $mappers = [
            'userId' => function ($value) {
                return $this->builder->where("{$this->mainTable}.userId", '=', $value);
            },

            'status' => function ($value) {
                return $this->builder->where("{$this->mainTable}.status", '=', $value);
            },

            'fullName' => function ($value) {
                return $this->builder->whereRaw($this->getFullNameField() . ' LIKE ?', ['%' . $value . '%']);
            },

            'dateRange' => function (DateRange $value) {
                $builder = $this->builder;

                if (!empty($startDate = $value->getStart())) {
                    $builder = $builder->where("{$this->mainTable}.created_at", '>=', $startDate);
                }

                if (!empty($endDate = $value->getEnd())) {
                    $builder = $builder->where("{$this->mainTable}.created_at", '<=', $endDate);
                }

                return $builder;
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

    /**
     * @return string
     */
    private function getFullNameField(): string
    {
        return "CONCAT(`{$this->billingTable}`.`firstName`, ' ', `{$this->billingTable}`.`lastName`)";
    }
}