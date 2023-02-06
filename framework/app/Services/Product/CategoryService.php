<?php

namespace App\Services\Product;

use App\Http\Requests\Product\CategoryRequest;
use App\Models\AbstractModel;
use App\Models\Product\Category;
use App\Repositories\Product\CategoryRepository;
use App\Services\AbstractService;
use App\Services\Tables;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class CategoryService
 * @package App\Services\Product
 */
class CategoryService extends AbstractService
{
    use Tables;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRequest    $request
     * @param Guard              $auth
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRequest $request, Guard $auth, CategoryRepository $repository)
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
                'name'
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
     * @return bool
     * @throws \Exception
     */
    public function destroy(): bool
    {
        /** @var Category $category */
        $category = $this->request->getModel();

        if (!$category->products->isEmpty()) {
            throw new \Exception('You cannot delete this category because is has products attached to it.');
        }

        return $this->repository->destroy($this->request->getModel());
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'name'     => $this->request->input('name'),
            'position' => $this->request->input('position'),
        ];
    }
}