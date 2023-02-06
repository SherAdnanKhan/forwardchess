<?php

namespace App\Policies\Order;

use App\Models\AbstractModel;
use App\Models\Order\Payment;
use App\Models\User\User;
use App\Policies\AbstractPolicy;
use App\Repositories\Order\OrderRepository;

/**
 * Class PaymentPolicy
 * @package App\Policies\Order
 */
class PaymentPolicy extends AbstractPolicy
{
    /**
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function show(User $user, AbstractModel $model): bool
    {
        return $this->isParentAuthorized($user, $model);
    }

    /**
     * @param User          $user
     * @param AbstractModel $model
     *
     * @return bool
     */
    private function isParentAuthorized(User $user, AbstractModel $model): bool
    {
        /** @var Payment $model */
        $parentResource = app(OrderRepository::class)->getById($model->orderId);

        return empty($parentResource) || $this->isAuthorized($user, $parentResource);
    }
}
