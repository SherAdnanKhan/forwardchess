<?php

namespace App\Policies;

use App\Models\AbstractModel;
use App\Models\User\User;

/**
 * Class AbstractSemiPublicPolicy
 * @package App\Policies\Product
 */
abstract class AbstractSemiPublicPolicy extends AbstractPolicy
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function index(User $user): bool
    {
        return true;
    }

    /**
     * @param User          $user
     * @param AbstractModel $model
     *
     * @return mixed
     */
    public function show(User $user, AbstractModel $model): bool
    {
        return true;
    }
}
