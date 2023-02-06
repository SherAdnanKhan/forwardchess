<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\NewsletterRepository;

/**
 * Class NewsletterRequest
 * @package App\Http\Requests
 */
class NewsletterRequest extends AbstractFormRequest
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
    ];

    /**
     * @var array
     */
    protected array $publicActions = [
        'store'
    ];

    /**
     * @var array
     */
    protected array $aliasActions = [
        'subscribe' => 'store',
        'restore'   => 'update'
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'newsletter';

    /**
     * @param NewsletterRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(NewsletterRepository $repository): bool
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
            'email' => ['required']
        ];
    }
}
