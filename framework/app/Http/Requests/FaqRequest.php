<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\FaqRepository;

/**
 * Class FaqRequest
 * @package App\Http\Requests
 */
class FaqRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
        'update',
        'destroy',
        'categories',
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
    private string $ownModelParam = 'faq';

    /**
     * @param FaqRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(FaqRepository $repository): bool
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
            'categoryId' => ['numeric'],
            'question'   => ['string', 'max:250'],
            'answer'     => ['string'],
            'position'   => ['numeric'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule($this->globalRules(), [], [
            'position',
        ]);
    }
}
