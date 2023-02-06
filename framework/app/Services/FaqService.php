<?php

namespace App\Services;

use App\Http\Requests\FaqRequest;
use App\Models\AbstractModel;
use App\Models\Faq\Category;
use App\Repositories\FaqRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class FaqService
 * @package App\Services
 */
class FaqService extends AbstractService
{
    use Tables;

    /**
     * PromotionService constructor.
     *
     * @param FaqRequest    $request
     * @param Guard         $auth
     * @param FaqRepository $repository
     */
    public function __construct(FaqRequest $request, Guard $auth, FaqRepository $repository)
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
                'categoryName',
                'question'
            ]
        );
    }

    /**
     * @return Category[]
     */
    public function getCategories()
    {
        return Category::all();
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
     * @param array $filters
     *
     * @return array
     */
    protected function initCollectionFilters(array $filters = []): array
    {
        if (!empty($categoryId = $this->request->input('categoryId'))) {
            $filters['categoryId'] = $categoryId;
        }

        return $filters;
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'categoryId' => $this->request->input('categoryId'),
            'question'   => $this->request->input('question'),
            'answer'     => $this->request->input('answer'),
            'position'   => $this->request->input('position'),
            'active'     => $this->request->input('active'),
        ];
    }
}