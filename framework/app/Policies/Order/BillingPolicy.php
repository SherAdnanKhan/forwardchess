<?php

namespace App\Policies\Order;

use App\Models\AbstractModel;
use App\Models\Order\Billing;
use App\Models\User\User;
use App\Policies\AbstractPolicy;
use App\Repositories\Order\OrderRepository;

/**
 * Class BillingPolicy
 * @package App\Policies\Order
 */
class BillingPolicy extends AbstractPolicy
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
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function update(User $user, AbstractModel $model): bool
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
        /** @var Billing $model */
        $parentResource = app(OrderRepository::class)->getById($model->orderId);

        return empty($parentResource) || $this->isAuthorized($user, $parentResource);
    }
}
