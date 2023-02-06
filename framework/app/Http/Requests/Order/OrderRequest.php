<?php

namespace App\Http\Requests\Order;

use App\Exceptions\AuthorizationException;
use App\Http\Requests\AbstractFormRequest;
use App\Repositories\Order\OrderRepository;

/**
 * Class OrderRequest
 * @package App\Http\Requests\Order
 */
class OrderRequest extends AbstractFormRequest
{
    /**
     * @var array
     */
    protected array $allowedActions = [
        'index',
        'show',
        'update',
        'destroy',
        'items',
        'placeOrder',
        'display'
    ];

    /**
     * @var array
     */
    protected array $aliasActions = [
        'tables'         => 'index',
        'home'           => 'index',
        'display'        => 'show',
        'search'         => 'index',
        'restore'        => 'update',
        'placeGiftOrder' => 'placeOrder',
    ];

    /**
     * @var string
     */
    private string $ownModelParam = 'order';

    /**
     * @var string
     */
    private string $displayModelParam = 'refNo';

    /**
     * @param OrderRepository $repository
     *
     * @return bool
     * @throws \App\Exceptions\AuthorizationException
     */
    public function authorize(OrderRepository $repository)
    {
        $this->getActionName();

        if (!empty($refNo = $this->route($this->displayModelParam))) {
            $routeParam = $repository->getIdFromRefno($refNo);
            if (empty($routeParam)) {
                redirect_now(route('site.orders.index'));
            }
        } else {
            $routeParam = $this->route($this->ownModelParam);
        }

        switch ($this->actionName) {
            case 'show':
            case 'update':
            case 'destroy':
            case 'items':
                $this->setModel($repository->getByIdOrFail($routeParam));
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
        switch ($this->getActionName()) {
            case 'update':
                return $this->updateRules();
            case 'placeOrder':
                return $this->placeOrderRules();
            default:
                return [];
        }
    }

    /**
     * @return array
     */
    protected function placeOrderRules(): array
    {
        return [
            'firstName' => 'required|string|max:255',
            'lastName'  => 'required|string|max:255',
        ];
    }
}
