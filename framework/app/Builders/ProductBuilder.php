<?php

namespace App\Builders;

use App\Assets\PriceRange;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductBuilder
 * @package App\Builders
 */
class ProductBuilder extends AbstractBuilder implements BuilderInterface
{
    const FILTER_NEW_ARRIVALS = 'new-arrivals';
    const FILTER_COMING_SOON  = 'coming-soon';

    /**
     * @var string
     */
    private string $publishersTable;

    /**
     * @var string
     */
    private string $categoriesTable;

    /**
     * @var array
     */
    private array $publishersIds = [];

    /**
     * @var array
     */
    private array $categoriesIds = [];

    /**
     * @var PriceRange
     */
    private PriceRange $priceRange;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var boolean
     */
    private bool $active;

    /**
     * @var string
     */
    private string $specialFilter;

    /**
     * @var string
     */
    private string $excludedId;

    /**
     * @param array $data
     *
     * @return ProductBuilder
     */
    public static function make(array $data = []): BuilderInterface
    {
        /** @var ProductBuilder $instance */
        $instance = app(ProductBuilder::class);

        $mapper = [
            'publisherId'   => 'setPublishersIds',
            'categoryId'    => 'setCategoriesIds',
            'publishersIds' => 'setPublishersIds',
            'categoriesIds' => 'setCategoriesIds',
            'priceRange'    => 'setPriceRange',
            'title'         => 'setTitle',
            'trashIncluded' => 'setIsTrashIncluded',
            'active'        => 'setActive',
            'sortBy'        => 'setSortBy',
            'specialFilter' => 'setSpecialFilter',
            'excludedId'    => 'setExcludedId'
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

            switch ($property) {
                case 'publisherId':
                case 'categoryId':
                    $value = [$value];
                    break;
            }

            call_user_func_array([$instance, $mapper[$property]], [$value]);
        }

        $instance->setBulkFields($bulkFields);

        return $instance;
    }

    /**
     * ProductBuilder constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->resource        = $product;
        $this->mainTable       = $product->getTable();
        $this->publishersTable = $product->publisher()->getRelated()->getTable();
        $this->categoriesTable = $product->categories()->getTable();
    }

    /**
     * @return Builder
     */
    public function init(): Builder
    {
        $this->builder = $this->isTrashIncluded ? $this->resource->withTrashed() : $this->resource->query();
        $this->builder = $this->builder->with(['publisher']);

        $this
            ->addFields()
            ->addJoins()
            ->addWhere()
            ->addSortBy();

//        $this->builder = $this->builder->groupBy('products.id');

        return $this->builder;
    }

    /**
     * @param array $publishersIds
     *
     * @return ProductBuilder
     */
    public function setPublishersIds(array $publishersIds): ProductBuilder
    {
        $this->publishersIds = $publishersIds;

        return $this;
    }

    /**
     * @param array $categoriesIds
     *
     * @return ProductBuilder
     */
    public function setCategoriesIds(array $categoriesIds): ProductBuilder
    {
        $this->categoriesIds = $categoriesIds;

        return $this;
    }

    /**
     * @param PriceRange $priceRange
     *
     * @return ProductBuilder
     */
    public function setPriceRange(PriceRange $priceRange): ProductBuilder
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return ProductBuilder
     */
    public function setTitle(string $title): ProductBuilder
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param bool $active
     *
     * @return ProductBuilder
     */
    public function setActive(bool $active): ProductBuilder
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @param string $specialFilter
     *
     * @return ProductBuilder
     */
    public function setSpecialFilter(string $specialFilter): ProductBuilder
    {
        $this->specialFilter = $specialFilter;

        return $this;
    }

    /**
     * @param string $excludedId
     *
     * @return ProductBuilder
     */
    public function setExcludedId(string $excludedId): ProductBuilder
    {
        $this->excludedId = $excludedId;

        return $this;
    }

    /**
     * @return ProductBuilder
     */
    private function addFields(): ProductBuilder
    {
        $fields = array_map(function ($field) {
            return "{$this->mainTable}.{$field}";
        }, [
            'id',
            'publisherId',
            'title',
            'description',
            'author',
            'sku',
            'url',
            'image',
            'price',
            'discountType',
            'discount',
            'discountStartDate',
            'discountEndDate',
            'nrPages',
            'totalSales',
            'level',
            'rating',
            'publishDate',
            'position',
            'active',
            'isBundle',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);

        $fields[] = DB::raw($this->getSellPriceField() . ' as sellPrice');
        $fields[] = DB::raw("`{$this->publishersTable}`.`name`" . ' as `publisherName`');
        $fields[] = DB::raw("IF(IFNULL(`CS`.`id`, 0) > 0, 1, 0) as `isComingSoon`");
//        $fields[] = DB::raw($this->getSellPriceField() . ' as `sellPrice`');

        $this->builder = $this->builder->select($fields);

        return $this;
    }

    /**
     * @return ProductBuilder
     */
    private function addJoins(): ProductBuilder
    {
        $this->builder = $this->builder->join($this->publishersTable, "{$this->mainTable}.publisherId", '=', "{$this->publishersTable}.id");
        $this->builder = $this->builder->leftJoin("{$this->categoriesTable} as CS", function ($join) {
            $join->on('CS.productId', '=', "{$this->mainTable}.id");
            $join->on('CS.categoryId', '=', DB::raw(Category::COMING_SOON));
        });

        if (!empty($this->categoriesIds)) {
            $this->builder = $this->builder->join($this->categoriesTable, 'products.id', '=', 'products_categories.productId');
        }

//        $this->builder = $this->builder->groupBy("{$this->mainTable}.id");

        return $this;
    }

    /**
     * @return ProductBuilder
     */
    private function addWhere(): ProductBuilder
    {
        $mappers = [
            'excludedId' => function ($value) {
                return $this->builder->where("{$this->mainTable}.id", '<>', $value);
            },

            'publishersIds' => function ($value) {
                return (count($value) > 1)
                    ? $this->builder->whereIn("{$this->mainTable}.publisherId", $value)
                    : $this->builder->where("{$this->mainTable}.publisherId", '=', $value);
            },

            'categoriesIds' => function ($value) {
                return $this->builder->whereIn("{$this->categoriesTable}.categoryId", $value);
            },

            'priceRange' => function (PriceRange $value) {
                return $this->builder->whereRaw($this->getSellPriceField() . ' between ' . $value->getMinPrice() . ' AND ' . $value->getMaxPrice());
            },

            'title' => function ($value) {
                return $this->builder->where(function ($query) use ($value) {
                    $query->where("{$this->mainTable}.title", 'like', "%{$value}%")
                        ->orWhere("{$this->mainTable}.author", 'like', "%{$value}%");
                });
            },

            'active' => function ($value) {
                return $this->builder->where("{$this->mainTable}.active", '=', $value ? 1 : 0);
            },

            'specialFilter' => function ($value) {
                if ($value === self::FILTER_NEW_ARRIVALS) {
                    return $this->builder->whereRaw('IF(IFNULL(`CS`.`id`, 0) > 0, 1, 0) = 0');
                } elseif ($value === self::FILTER_COMING_SOON) {
                    return $this->builder->whereRaw('IF(IFNULL(`CS`.`id`, 0) > 0, 1, 0) = 1');
                }

                return $this->builder;
            },
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
    private function getSellPriceField(): string
    {
        return 'IF(
            `discount` > 0 AND `discountStartDate` <= NOW() AND `discountEndDate` >= NOW(), 
            IF (
                `discountType` = \'' . Product::TYPE_AMOUNT . '\', 
                `discount`,
                ROUND(`price` - `price` * `discount` / 10000)
            ),
            `price`
        )';
    }
}