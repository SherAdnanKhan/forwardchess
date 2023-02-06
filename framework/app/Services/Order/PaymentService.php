<?php

namespace App\Services\Order;

use App\Http\Requests\Order\PaymentRequest;
use App\Models\AbstractModel;
use App\Repositories\Order\PaymentRepository;
use App\Services\AbstractService;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class PaymentService
 * @package App\Services\Order
 */
class PaymentService extends AbstractService
{
    /**
     * PaymentService constructor.
     *
     * @param PaymentRequest    $request
     * @param Guard             $auth
     * @param PaymentRepository $repository
     */
    public function __construct(PaymentRequest $request, Guard $auth, PaymentRepository $repository)
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