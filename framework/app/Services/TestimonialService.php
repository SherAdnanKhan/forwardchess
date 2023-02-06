<?php

namespace App\Services;

use App\Http\Requests\TestimonialRequest;
use App\Models\AbstractModel;
use App\Repositories\TestimonialRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class TestimonialService
 * @package App\Services
 */
class TestimonialService extends AbstractService
{
    use Tables;

    /**
     * PromotionService constructor.
     *
     * @param TestimonialRequest    $request
     * @param Guard                 $auth
     * @param TestimonialRepository $repository
     */
    public function __construct(TestimonialRequest $request, Guard $auth, TestimonialRepository $repository)
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
            $this->repository->getBuilder($this->initCollectionFilters()),
            [
                'user'
            ]
        );
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        return $this->repository->store($this->processFields());
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
    private function getRequestFormFields(): array
    {
        return $this->getRequestFields($this->request, [
            'user'        => null,
            'video'       => '',
            'description' => '',
            'active'      => false,
        ]);
    }
}