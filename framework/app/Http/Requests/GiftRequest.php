<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\GiftRepository;

/**
 * Class GiftRequest
 * @package App\Http\Requests
 */
class GiftRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'store',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'gift';

    /**
     * @param GiftRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(GiftRepository $repository): bool
    {
        $this->getActionName();

        if (($this->actionName === 'store') && !$this->user()) {
            return false;
        }

        switch ($this->actionName) {
            case 'show':
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
            'amount'        => ['numeric'],
            'friendEmail'   => ['string'],
            'friendName'    => ['string'],
            'friendMessage' => ['string'],
            'orderId'       => ['numeric'],
        ];
    }

    /**
     * @return array
     */
    protected function storeRules(): array
    {
        return $this->addRequiredRule($this->globalRules(), [], ['friendMessage', 'orderId']);
    }
}
