<?php

namespace App\Http\Requests\Product;

use App\Exceptions\AuthorizationException;
use App\Http\Requests\AbstractFormRequest;
use App\Repositories\Product\ReviewRepository;

/**
 * Class ReviewRequest
 * @package App\Http\Requests\Product
 */
class ReviewRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
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
    private string $ownModelParam = 'review';

    /**
     * @param ReviewRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(ReviewRepository $repository): bool
    {
        $this->getActionName();

        switch ($this->actionName) {
            case 'show':
            case 'update':
                $this->setModel($repository->getByIdOrFail((int)$this->route($this->ownModelParam)));
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
            'productId'   => ['numeric'],
            'title'       => ['string', 'max:250'],
            'description' => ['string'],
            'rating'      => ['numeric'],
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
