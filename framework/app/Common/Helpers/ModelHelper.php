<?php

namespace App\Common\Helpers;

use App\Exceptions\AuthorizationException;
use App\Exceptions\CommonException;
use App\Models\AbstractModel;
use App\Models\User\User;
use Illuminate\Support\Facades\Gate;

/**
 * Trait ModelHelper
 * @package App\Common\Helpers
 */
trait ModelHelper
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var \App\Models\AbstractModel
     */
    protected $model;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return ModelHelper
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return AbstractModel
     */
    public function getModel(): AbstractModel
    {
        return $this->model;
    }

    /**
     * @param AbstractModel $model
     *
     * @return ModelHelper
     */
    public function setModel(AbstractModel $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param AbstractModel $model
     * @param string $action
     *
     * @return bool
     */
    public function isAuthorized(AbstractModel $model, string $action): bool
    {
        return Gate::forUser($this->getUser())->allows($action, $model);
    }

    /**
     * @param $action
     * @param AbstractModel|null $model
     *
     * @return AbstractModel
     * @throws AuthorizationException
     * @throws CommonException
     */
    public function getAuthorizedModel($action, AbstractModel $model = null): AbstractModel
    {
        $model = $model ?: $this->getModel();

        /* @var AbstractModel $model */
        if (empty($model)) {
            throw new CommonException('Invalid model', 'INVALID_MODEL');
        }

        if (!$this->isAuthorized($model, $action)) {
            throw new AuthorizationException;
        }

        return $model;
    }

    /**
     * @param AbstractModel $model
     * @param $action
     *
     * @return AbstractModel
     */
    public function setAndAuthorizeModel(AbstractModel $model, $action): AbstractModel
    {
        return $this
            ->setModel($model)
            ->getAuthorizedModel($action);
    }
}
