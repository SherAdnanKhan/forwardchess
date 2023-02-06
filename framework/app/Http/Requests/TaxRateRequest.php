<?php

namespace App\Http\Requests;

use App\Exceptions\AuthorizationException;
use App\Repositories\TaxRateRepository;

/**
 * Class TaxRateRequest
 * @package App\Http\Requests\Blog
 */
class TaxRateRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'update'
    ];

    /**
     * @var array
     */
    protected array $publicActions = [
        'index',
        'show',
        'display',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'tax_rate';

    /**
     * @param TaxRateRepository $repository
     *
     * @return bool
     * @throws AuthorizationException
     */
    public function authorize(TaxRateRepository $repository): bool
    {
        $this->getActionName();

        switch ($this->actionName) {
            case 'show':
            case 'update':
                $this->setModel($repository->getByIdOrFail($this->route($this->ownModelParam)));
                break;
            default:
                $this->setModelClass(get_class($repository->getResource()));
                break;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function globalRules(): array
    {
        return [
            'country' => ['string'],
            'rate'    => ['numeric'],
            'name'    => ['string']
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
