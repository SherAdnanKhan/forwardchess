<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\SubscriberRepository;

/**
 * Class SubscriberRequest
 * @package App\Http\Requests
 */
class SubscriberRequest extends AbstractFormRequest
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
    private string $ownModelParam = 'subscriber';

    /**
     * @param SubscriberRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(SubscriberRepository $repository): bool
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
            'validity'       => ['numeric'],
            'valid_from'     => ['date'],
            'valid_to'       => ['date'],
            'item_number'    => ['string'],
            'txn_id'         => ['string'],
            'payment_gross'  => ['string'],
            'currency_code'  => ['string'],
            'subscr_id'      => ['string'],
            'payer_email'    => ['string'],
            'payment_status' => ['string'],
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
