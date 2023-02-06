<?php

namespace App\Rules;

use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class CreateUserUniqueAttributeRule
 * @package App\Rules
 */
class CreateUserUniqueAttributeRule implements Rule
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * Create a new rule instance.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed $attribute
     * @param  mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /** @var User $user */
        $user = $this->repository->setTrashedIncludedInSearch(true)->first([$attribute => $value]);
        if ($user) {
            return $user->trashed();
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
