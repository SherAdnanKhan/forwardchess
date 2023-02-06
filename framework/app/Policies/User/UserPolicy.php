<?php

namespace App\Policies\User;

use App\Models\AbstractModel;
use App\Models\User\User;
use App\Policies\AbstractPolicy;

/**
 * Class UserPolicy
 * @package App\Policies\User
 */
class UserPolicy extends AbstractPolicy
{
    /**
     * @param  User $user
     *
     * @return mixed
     */
    public function index(User $user): bool
    {
        return false;
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

    /**
     * @param  User $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function destroy(User $user, AbstractModel $model): bool
    {
        return $user->isSuperAdmin();
    }
}
