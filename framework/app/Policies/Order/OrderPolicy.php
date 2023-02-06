<?php

namespace App\Policies\Order;

use App\Models\AbstractModel;
use App\Models\Order\Order;
use App\Models\User\User;
use App\Policies\AbstractPolicy;

/**
 * Class OrderPolicy
 * @package App\Policies\User
 */
class OrderPolicy extends AbstractPolicy
{
    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function home(User $user): bool
    {
        return true;
    }

    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function index(User $user): bool
    {
        return true;
    }

    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function store(User $user): bool
    {
        return false;
    }

    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function placeOrder(User $user): bool
    {
        return true;
    }

    /**
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function show(User $user, AbstractModel $model): bool
    {
        return ($user->id === $model->user->id);
    }

    /**
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function items(User $user, AbstractModel $model): bool
    {
        return ($user->id === $model->id);
    }

    /**
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function update(User $user, AbstractModel $model): bool
    {
        /** @var Order $model */
        return ($user->id === $model->user->id);
    }

    /**
     * @param  User          $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function destroy(User $user, AbstractModel $model): bool
    {
        return false;
    }

    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function export(User $user): bool
    {
        return false;
    }
}
