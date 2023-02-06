<?php

namespace App\Http\Controllers\Site\Traits;

use App\Assets\ControllerSiteData;
use App\Assets\PriceRange;
use App\Assets\SortBy;
use App\Contracts\EcommerceInterface;
use App\Models\Product\Category;
use App\Services\Product\ProductService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Trait ProductsListing
 * @package App\Http\Controllers\Site\Traits
 *
 * @property ProductService     $productService
 * @property Category           $category
 * @property ControllerSiteData $data
 *
 * @method self addViewData(array $data)
 */
trait ProductsListing
{
    /**
     * @var string
     */
    private $specialFilter = null;

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var integer
     */
    private $rowsPerPage = 24;

    /**
     * @var SortBy
     */
    private $sortBy;

    /**
     * @return LengthAwarePaginator
     */
    private function getProducts(): LengthAwarePaginator
    {
        $this->queryParams = [];
        $this->filters     = [];

        $this->setFilters()
            ->setListOptions()
            ->makePageFilters();

        $products = $this->productService->paginate(
            $this->filters,
            $this->rowsPerPage,
            $this->sortBy,
            $this->queryParams
        );

        if (isset($this->filters['title'])) {
            $products->withPath('products?search=' . $this->filters['title']);
        }

        $pageTitle = $this->getPageTitle();

        /** @var EcommerceInterface $ecommerceService */
        $ecommerceService = app(EcommerceInterface::class);
        $ecommerceService
            ->setListName($pageTitle)
            ->addProducts($products->items());

        $this->addViewData([
            'headerSearch' => $this->filters['title'] ?? '',
            'pageTitle'    => $pageTitle,
            'products'     => $products
        ]);

        return $products;
    }

    private function getPageTitle(): string
    {
        return $this->category
            ? $this->category->name
            : ($this->specialFilter ? $this->specialFilter : 'Products');
    }

    /**
     * @param array $queryParams
     *
     * @return string
     */
    private function makeUrl(array $queryParams): string
    {
        $params = [];
        foreach ($queryParams as $name => $value) {
            if (empty($value)) {
                continue;
            }

            $params[$name] = $value;
        }

        $categoryUrl = $this->category ? $this->category->url : null;

        return productsPageUrl($categoryUrl, null, null, $params);
    }

    /**
     * @return self
     */
    private function setListOptions(): self
    {
        $rowsPerPageOptions = [12, 24, 48, 80];
        $sortByOptions      = [
            'name'        => [
                'label'     => 'name',
                'field'     => 'title',
                'direction' => 'asc'
            ],
            'priceAsc'    => [
                'label'     => 'price ascending',
                'field'     => 'price',
                'direction' => 'asc'
            ],
            'priceDesc'   => [
                'label'     => 'price descending',
                'field'     => 'price',
                'direction' => 'desc'
            ],
            'bestSeller'  => [
                'label'     => 'best sold',
                'field'     => 'totalSales',
                'direction' => 'desc'
            ],
            'releaseDate' => [
                'label'     => 'release date',
                'field'     => 'publishDate',
                'direction' => 'desc'
            ]
        ];

        $defaultSortBy      = 'name';
        $defaultRowsPerPage = 12;

        $sortBy      = $this->productService->getRequest()->input('sortBy', $defaultSortBy);
        $rowsPerPage = (int)$this->productService->getRequest()->input('perPage', $defaultRowsPerPage);

        $sortByOption = $sortByOptions[$sortBy] ?? $sortByOptions[$defaultSortBy];
        $rowsPerPage  = in_array($rowsPerPage, $rowsPerPageOptions) ? $rowsPerPage : $defaultRowsPerPage;

        if ($sortBy !== $defaultSortBy) {
            $this->queryParams['sortBy'] = $sortBy;
        }

        if ($rowsPerPage !== $defaultRowsPerPage) {
            $this->queryParams['perPage'] = $rowsPerPage;
        }

        $this->addViewData([
            'rowsPerPageOptions' => $rowsPerPageOptions,
            'sortOptions'        => $sortByOptions,
            'rowsPerPage'        => $rowsPerPage,
            'sortBy'             => $sortBy
        ]);

        $this->rowsPerPage = $rowsPerPage;
        $this->sortBy      = SortBy::make($sortByOption['field'], $sortByOption['direction']);

        return $this;
    }

    /**
     * @return self
     */
    private function setFilters(): self
    {
        $this->filters['active'] = 1;

        if (!empty($this->category)) {
            $this->filters['categoryId'] = $this->category->id;
        }

        if (!empty($this->specialFilter)) {
            $this->filters['specialFilter'] = $this->specialFilter;
        }

        $publishersNames = $this->productService->getRequest()->input('publishers');
        if ($publishersNames) {
            $publishers = [];

            foreach ($this->globalData->publishers as $publisher) {
                $searchValue = kebab_case($publisher->name);

                if (in_array($searchValue, $publishersNames)) {
                    $publishers[$publisher->id] = $searchValue;
                }

                if (count($publishers) === count($publishersNames)) {
                    break;
                }
            }

            if (count($publishers)) {
                $this->filters['publishersIds']  = array_keys($publishers);
                $this->queryParams['publishers'] = array_values($publishers);
            }
        }

        $priceRange = $this->productService->getRequest()->input('priceRange');
        if ($priceRange) {
            $priceRange = explode(',', $priceRange);
            if (count($priceRange) === 2) {
                [$minPrice, $maxPrice] = $priceRange;

                $minPrice = (int)$minPrice;
                $maxPrice = (int)$maxPrice;

                $this->filters['priceRange'] = PriceRange::make($minPrice, $maxPrice);

                $this->queryParams['priceRange'] = "{$minPrice},{$maxPrice}";
            }
        }

        $title = $this->productService->getRequest()->input('search');
        if (!empty($title)) {
            $this->filters['title'] = $title;
        }

        return $this;
    }

    /**
     * @return self
     */
    private function makePageFilters(): self
    {
        $this->addViewData([
            'filters' => [
                'categoryId'   => empty($this->category) ? null : $this->category->id,
                'publishers'   => $this->getPublishersFilterList(),
                'price'        => $this->getPriceFilter(),
                'submitAction' => $this->makeUrl($this->queryParams),
                'formAction'   => $this->makeUrl([]),
            ]
        ]);

        return $this;
    }

    /**
     * @return array
     */
    private function getPublishersFilterList(): array
    {
        $queryParams = $this->queryParams;
        if (!isset($queryParams['publishers'])) {
            $queryParams['publishers'] = [];
        }

        $publishers = [];
        $this->globalData->publishers->each(function ($publisher) use (&$publishers, $queryParams) {
            if (!isset($publishers[$publisher->id])) {
                $value = kebab_case($publisher->name);

                $publishers[$publisher->id] = [
                    'id'      => $publisher->id,
                    'label'   => trim($publisher->name),
                    'value'   => $value,
                    'checked' => in_array($value, $queryParams['publishers'])
                ];
            }
        });

        $filters = $this->filters;
        unset($filters['publishersIds']);

        $products = $this->productService->all($filters);

        if ($products->count()) {
            $productsCount = [];
            foreach ($products as $product) {
                if (!isset($productsCount[$product->publisherId])) {
                    $productsCount[$product->publisherId] = 0;
                }

                $productsCount[$product->publisherId]++;
            }

            $publishers = array_map(function ($publisher) use ($productsCount) {
                $publisher['products'] = isset($productsCount[$publisher['id']]) ? $productsCount[$publisher['id']] : 0;

                return $publisher;
            }, $publishers);

            usort($publishers, function ($a, $b) {
                if ($a['products'] < $b['products']) {
                    return 1;
                } elseif ($a['products'] > $b['products']) {
                    return -1;
                } else {
                    return 0;
                }
            });
        }

        return $publishers;
    }

    /**
     * @return array
     */
    private function getPriceFilter(): array
    {
        /** @var PriceRange $priceRange */
        $priceRange = isset($this->filters['priceRange']) ? $this->filters['priceRange'] : null;
        if ($priceRange) {
            return [
                'label' => '$' . toFloatAmount($priceRange->getMinPrice()) . ' - $' . toFloatAmount($priceRange->getMaxPrice()),
                'value' => $this->queryParams['priceRange']
            ];
        } else {
            return [
                'label' => '',
                'value' => ''
            ];
        }
    }

    private function setSpecialFilter(string $filter)
    {
        $this->specialFilter = $filter;
    }
}
