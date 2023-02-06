<?php

namespace App\Policies;

use App\Models\AbstractModel;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

/**
 * Class AbstractPolicy
 * @package App\Policies
 */
abstract class AbstractPolicy implements PolicyInterface
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param string $ability
     *
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin) {
            return true;
        }
    }

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
     *
     * @return mixed
     */
    public function tables(User $user): bool
    {
        return $this->index($user);
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
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function show(User $user, AbstractModel $model): bool
    {
        return false;
    }

    /**
     * @param  User $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function update(User $user, AbstractModel $model): bool
    {
        return false;
    }

    /**
     * @param  User $user
     * @param  AbstractModel $model
     *
     * @return mixed
     */
    public function destroy(User $user, AbstractModel $model): bool
    {
        return false;
    }

    /**
     * @param User $user
     * @param AbstractModel $model
     * @param string $actionName
     *
     * @return bool
     */
    protected function isAuthorized(User $user, AbstractModel $model, string $actionName = 'show'):bool
    {
        return Gate::forUser($user)->allows($actionName, $model);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    protected function isPartnerMainUser(User $user):bool
    {
        return ($user->isPartner && (Partner::getMainUser($user->partnerId) === $user->id));
    }
}