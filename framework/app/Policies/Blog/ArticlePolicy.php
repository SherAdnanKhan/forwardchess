<?php

namespace App\Policies\Blog;

use App\Models\User\User;
use App\Policies\AbstractSemiPublicPolicy;

/**
 * Class ArticlePolicy
 * @package App\Policies\Product
 */
class ArticlePolicy extends AbstractSemiPublicPolicy
{
    /**
     * @param User $user
     *
     * @return mixed
     */
    public function export(User $user): bool
    {
        return false;
    }
}
