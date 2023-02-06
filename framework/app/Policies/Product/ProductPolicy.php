<?php

namespace App\Policies\Product;

use App\Models\User\User;
use App\Policies\AbstractSemiPublicPolicy;

/**
 * Class ProductPolicy
 * @package App\Policies\Product
 */
class ProductPolicy extends AbstractSemiPublicPolicy
{
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
