<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\AbstractFormRequest;
use App\Repositories\Product\CategoryRepository;

/**
 * Class CategoryRequest
 * @package App\Http\Requests\Product
 */
class CategoryRequest extends AbstractFormRequest
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
     * @var array
     */
    protected array $publicActions = [
        'index',
        'show'
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'category';

    /**
     * @param CategoryRepository $repository
     *
     * @return bool
     * @throws \App\Exceptions\AuthorizationException
     */
    public function authorize(CategoryRepository $repository)
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

    protected function globalRules(): array
    {
        return [
            'name' => ['string', 'max:250']
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule($this->globalRules());
    }
}
