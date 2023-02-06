<?php

namespace App\Services;

use App\Models\Subscriber;
use App\Models\Faq\Category;
use App\Models\AbstractModel;
use App\Http\Requests\FaqRequest;
use App\Repositories\FaqRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubscriberRequest;
use App\Repositories\SubscriberRepository;

/**
 * Class FaqService
 * @package App\Services
 */
class SubscriberService extends AbstractService
{
    use Tables;

    /**
     * PromotionService constructor.
     *
     * @param SubscriberRequest    $request
     * @param Guard         $auth
     * @param SubscriberRepository $repository
     */
    public function __construct(SubscriberRequest $request, Guard $auth, SubscriberRepository $repository)
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
     * @return Subscriber[]
     */
    public function getCategories()
    {
        return Subscriber::all();
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
     * @return Item[]
     */
    public function getItems()
    {
        /** @var Subscriber $subscriber */
        $subscriber = $this->request->getModel();

        return $subscriber->items;
    }
}
