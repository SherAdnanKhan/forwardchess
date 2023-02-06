<?php

namespace App\Services\User;

use App\Http\Requests\User\UserRequest;
use App\Models\AbstractModel;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use App\Searches\UserSearch;
use App\Services\AbstractService;
use App\Services\Tables;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class UserService
 * @package App\Services\User
 */
class UserService extends AbstractService
{
    use Tables;

    /**
     * UserService constructor.
     *
     * @param UserRequest    $request
     * @param Guard          $auth
     * @param UserRepository $repository
     */
    public function __construct(UserRequest $request, Guard $auth, UserRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder($this->initCollectionFilters()),
            [
                'email',
                'firstName',
                'lastName'
            ]
        );
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields([
            'email'      => $this->request->input('email'),
            'password'   => md5($this->request->input('password')),
            'firstName'  => $this->request->input('firstName'),
            'lastName'   => $this->request->input('lastName'),
            'active'     => $this->request->input('active'),
            'nickname'   => $this->request->input('nickname'),
            'subscribed' => $this->request->input('subscribed'),
        ]);

        $fields = $this->processFields();

        return $this->repository->store($fields);
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     */
    public function update($restore = false): AbstractModel
    {
        $model = $this->request->getModel();

        $this->setFormFields([
            'firstName'  => $this->request->input('firstName'),
            'lastName'   => $this->request->input('lastName'),
            'nickname'   => $this->request->input('nickname'),
            'subscribed' => $this->request->input('subscribed'),
        ]);

        if ($this->request->user()->isAdmin) {
            $this->addFormFields([
                'active' => $this->request->input('active')
            ]);
        }

        return $this->repository->update($model, $this->processFields(), $restore);
    }

    /**
     * @return AbstractModel
     */
    public function activate()
    {
        return $this->repository->update($this->request->getModel(), ['email_verified_at' => Carbon::now()->toDateTimeString()]);
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        /** @var User $user */
        $user = $this->request->user();
        if (!$user->isAdmin) {
            $filters['id'] = $user->id;
        }

        /** @var UserSearch $search */
        $search = app(UserSearch::class, ['data' => $this->request->all()]);

        return array_merge($filters, $search->getFilters());
    }
}
