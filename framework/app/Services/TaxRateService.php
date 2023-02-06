<?php

namespace App\Services;

use App\Http\Requests\TaxRateRequest;
use App\Models\AbstractModel;
use App\Repositories\TaxRateRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class TaxRateService
 * @package App\Services\Blog
 */
class TaxRateService extends AbstractService
{
    use Tables;

    /**
     * TaxRateService constructor.
     *
     * @param TaxRateRequest    $request
     * @param Guard             $auth
     * @param TaxRateRepository $repository
     */
    public function __construct(TaxRateRequest $request, Guard $auth, TaxRateRepository $repository)
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
                'country'
            ]
        );
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
     * @return bool
     */
    public function destroy(): bool
    {
        abort(403, 'Unauthorized action.');
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'country' => $this->request->input('country'),
            'rate'    => $this->request->input('rate'),
            'name'    => $this->request->input('name'),
        ];
    }
}