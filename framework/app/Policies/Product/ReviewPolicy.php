<?php

namespace App\Policies\Product;

use App\Models\AbstractModel;
use App\Models\User\User;
use App\Policies\AbstractPolicy;

/**
 * Class ReviewPolicy
 * @package App\Policies\Product
 */
class ReviewPolicy extends AbstractPolicy
{
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
        return true;
    }

    /**
     * @param  User $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function show(User $user, AbstractModel $model): bool
    {
        return ($user->id === $model->id);
    }

    /**
     * @param  User $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function update(User $user, AbstractModel $model): bool
    {
        return ($user->id === $model->id);
    }
}
