<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use App\Rules\CouponProductsRule;
use Illuminate\Validation\Rule;

/**
 * Class CouponRequest
 * @package App\Http\Requests
 */
class CouponRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
        'destroy'
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'coupon';

    /**
     * @param CouponRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(CouponRepository $repository): bool
    {
        $this->getActionName();

        switch ($this->actionName) {
            case 'show':
            case 'update':
            case 'destroy':
                $this->setModel($repository->getByIdOrFail($this->route($this->ownModelParam)));
                break;
            default:
                $this->setModelClass(get_class($repository->getResource()));
                break;
        }

        return $this->isActionAuthorized();
    }

    protected function globalRules(): array
    {
        $typesList      = [Coupon::TYPE_PERCENT, Coupon::TYPE_AMOUNT];
        $codeUniqueRule = Rule::unique('coupons');
        if (!empty($id = $this->route($this->ownModelParam))) {
            $codeUniqueRule = $codeUniqueRule->ignore($id, 'id');
        }

        return [
            'type'             => ['in:' . implode(',', $typesList)],
            'name'             => ['string', 'max:250'],
            'code'             => ['string', 'max:100', $codeUniqueRule],
            'discount'         => ['numeric'],
            'startDate'        => ['date_format:Y-m-d'],
            'endDate'          => ['date_format:Y-m-d'],
            'minAmount'        => ['numeric'],
            'usageLimit'       => ['numeric'],
            'uniqueOnUser'     => ['boolean'],
            'excludeDiscounts' => ['boolean'],
            'firstPurchase'    => ['boolean'],
            'products'         => ['array', app(CouponProductsRule::class)]
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule(
            $this->globalRules(),
            [],
            [
                'minAmount',
                'usageLimit',
                'uniqueOnUser',
                'excludeDiscounts',
                'firstPurchase',
                'products'
            ]
        );
    }
}
