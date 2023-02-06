<?php

namespace App\Services;

use App\Http\Requests\GiftRequest;
use App\Models\AbstractModel;
use App\Repositories\GiftRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class GiftService
 * @package App\Services
 */
class GiftService extends AbstractService
{
    use Tables;

    /**
     * GiftService constructor.
     *
     * @param GiftRequest    $request
     * @param Guard          $auth
     * @param GiftRepository $repository
     */
    public function __construct(GiftRequest $request, Guard $auth, GiftRepository $repository)
    {
        parent::__construct($request, $auth, $repository);
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder(),
            [
                'code'
            ]
        );
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        $fields           = $this->processFields();
        $fields['userId'] = $this->request->user()->id;

        return $this->repository->store($fields);
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'amount'        => $this->request->input('amount'),
            'friendEmail'   => $this->request->input('friendEmail'),
            'friendName'    => $this->request->input('friendName'),
            'friendMessage' => $this->request->input('friendMessage'),
            'orderId'       => $this->request->input('orderId'),
            'enabled'       => $this->request->input('enabled'),
        ];
    }
}