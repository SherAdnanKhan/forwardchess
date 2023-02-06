<?php

namespace App\Policies;

use App\Models\User\User;

/**
 * Class FaqPolicy
 * @package App\Policies
 */
class FaqPolicy extends AbstractSemiPublicPolicy
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function categories(User $user): bool
    {
        return false;
    }
}
