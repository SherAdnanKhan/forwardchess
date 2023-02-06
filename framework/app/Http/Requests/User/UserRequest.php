<?php

namespace App\Http\Requests\User;

use App\Exceptions\AuthorizationException;
use App\Http\Requests\AbstractFormRequest;
use App\Repositories\User\UserRepository;

class UserRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
        'destroy'
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'user';

    /**
     * @var array
     */
    protected array $aliasActions = [
        'tables'        => 'index',
        'display'       => 'show',
        'activate'      => 'update',
        'search'        => 'index',
        'restore'       => 'update',
        'createAccount' => 'store'
    ];

    protected array $publicActions = [
        'store',
    ];

    /**
     * @param UserRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(UserRepository $repository): bool
    {
        $this->getActionName();

        switch ($this->actionName) {
            case 'show':
            case 'update':
            case 'destroy':
                $this->setModel($repository->getByIdOrFail($this->route($this->ownModelParam)));
                break;
            default:
                $this->setModelClass(get_class($repository->getResource()));
                break;
        }

        return $this->isActionAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws AuthorizationException
     */
    public function rules(): array
    {
        return ($this->originalAction === 'activate') ? [] : parent::rules();
    }

    protected function globalRules(): array
    {
        return [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName'  => ['required', 'string', 'max:255'],
            'nickname'  => ['string', 'max:100'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return array_merge([
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => 'required|string|min:8|max:100',
        ], $this->globalRules());
    }
}
