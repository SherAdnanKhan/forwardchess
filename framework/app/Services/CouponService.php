<?php

namespace App\Services;

use App\Http\Requests\CouponRequest;
use App\Models\AbstractModel;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class PromotionService
 * @package App\Services
 */
class CouponService extends AbstractService
{
    use Tables;

    /**
     * PromotionService constructor.
     *
     * @param CouponRequest    $request
     * @param Guard            $auth
     * @param CouponRepository $repository
     */
    public function __construct(CouponRequest $request, Guard $auth, CouponRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder(),
            [
                'name'
            ]
        );
    }

    /**
     * @return AbstractModel
     * @throws \App\Exceptions\CommonException
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        $fields   = $this->processFields();
        $products = $this->getField($fields, 'products');

        /** @var CouponRepository $repository */
        $repository = $this->repository;

        /** @var Coupon $model */
        $model = $repository->store($fields);

        if ($products) {
            $model = $repository->saveProducts($model, $products);
        }

        return $model;
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     * @throws \App\Exceptions\CommonException
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Coupon $model */
        $model = $this->request->getModel();

        /** @var CouponRepository $repository */
        $repository = $this->repository;

        $fields   = $this->processFields();
        $products = $this->getField($fields, 'products');

        if (!empty($fields)) {
            $model = $repository->update($model, $fields, $restore);
        }

        if ($products) {
            $model = $repository->saveProducts($model, $products);
        }

        return $model;
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
//        /** @var ProductSearch $search */
//        $search = app(ProductSearch::class, ['data' => $this->request->all()]);
//
//        return array_merge($search->getFilters(), $filters);

        return $filters;
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'type'             => $this->request->input('type'),
            'name'             => $this->request->input('name'),
            'code'             => $this->request->input('code'),
            'discount'         => $this->request->input('discount'),
            'minAmount'        => $this->request->input('minAmount'),
            'usageLimit'       => $this->request->input('usageLimit'),
            'uniqueOnUser'     => $this->request->input('uniqueOnUser'),
            'excludeDiscounts' => $this->request->input('excludeDiscounts'),
            'firstPurchase'    => $this->request->input('firstPurchase'),
            'startDate'        => $this->request->input('startDate'),
            'endDate'          => $this->request->input('endDate'),
            'products'         => $this->request->input('products'),
        ];
    }

    /**
     * @param array  $fields
     * @param string $fieldName
     *
     * @return mixed|null
     */
    private function getField(array &$fields, string $fieldName)
    {
        $value = isset($fields[$fieldName]) ? $fields[$fieldName] : null;

        unset($fields[$fieldName]);

        return $value;
    }
}