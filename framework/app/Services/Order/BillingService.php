<?php

namespace App\Services\Order;

use App\Http\Requests\Order\BillingRequest;
use App\Models\AbstractModel;
use App\Repositories\Order\BillingRepository;
use App\Services\AbstractService;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class BillingService
 * @package App\Services\Order
 */
class BillingService extends AbstractService
{
    /**
     * BillingService constructor.
     *
     * @param BillingRequest    $request
     * @param Guard             $auth
     * @param BillingRepository $repository
     */
    public function __construct(BillingRequest $request, Guard $auth, BillingRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        return $this->repository->update($this->request->getModel(), $this->processFields(), $restore);
    }

    /**
     * @return array
     */
    protected function getRequestFormFields(): array
    {
        return [
            'user'    => $this->request->input('user'),
            'main'    => $this->request->input('main'),
            'address' => $this->request->input('address'),
            'city'    => $this->request->input('city'),
            'state'   => $this->request->input('state'),
            'zipCode' => $this->request->input('zipCode'),
        ];
    }
}