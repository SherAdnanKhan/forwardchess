<?php

namespace App\Policies;

use App\Models\AbstractModel;
use App\Models\User\User;

/**
 * Interface PolicyInterface
 * @package App\Policies
 */
interface PolicyInterface
{
    /**
     * determine whether the user can view the resource collection
     *
     * @param User $user
     *
     * @return bool
     */
    public function index(User $user): bool;

    /**
     * determine whether the user can create a new resource
     *
     * @param User $user
     *
     * @return bool
     */
    public function store(User $user): bool;

    /**
     * determine whether the user can view the resource
     *
     * @param User $user
     * @param AbstractModel $model
     *
     * @return bool
     */
    public function show(User $user, AbstractModel $model): bool;

    /**
     * determine whether the user can update the resource
     *
     * @param User $user
     * @param AbstractModel $model
     *
     * @return bool
     */
    public function update(User $user, AbstractModel $model): bool;

    /**
     * determine whether the user can destroy the resource
     *
     * @param User $user
     * @param AbstractModel $model
     *
     * @return bool
     */
    public function destroy(User $user, AbstractModel $model): bool;
}