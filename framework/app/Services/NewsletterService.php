<?php

namespace App\Services;

use App\Contracts\SubscribersGatewayInterface;
use App\Http\Requests\NewsletterRequest;
use App\Models\AbstractModel;
use App\Models\Newsletter;
use App\Repositories\NewsletterRepository;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class NewsletterService
 * @package App\Services
 */
class NewsletterService extends AbstractService
{
    use Tables;

    /**
     * @var SubscribersGatewayInterface
     */
    private $subscribersGateway;

    /**
     * PromotionService constructor.
     *
     * @param NewsletterRequest           $request
     * @param Guard                       $auth
     * @param NewsletterRepository        $repository
     * @param SubscribersGatewayInterface $subscribersGateway
     */
    public function __construct(NewsletterRequest $request, Guard $auth, NewsletterRepository $repository, SubscribersGatewayInterface $subscribersGateway)
    {
        parent::__construct($request, $auth, $repository);

        $this->subscribersGateway = $subscribersGateway;
    }

    /**
     * @return array
     */
    public function tables(): array
    {
        return $this->getTable(
            $this->request,
            $this->repository->getBuilder($this->initCollectionFilters())
        );
    }

    /**
     * @return AbstractModel
     */
    public function store(): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        $fields = $this->processFields();

        /** @var Newsletter $newsletter */
        $newsletter = $this->repository->setTrashedIncludedInSearch(true)->first(['email' => $fields['email']]);
        if (!$newsletter) {
            $newsletter = $this->repository->store($fields);

            $this->subscribersGateway->subscribe($newsletter);

            return $newsletter;
        }

        if ($newsletter->trashed()) {
            $this->request->setModel($newsletter);

            return $this->update(true);
        } else {
            return $newsletter;
        }
    }

    /**
     * @param bool $restore
     *
     * @return AbstractModel
     */
    public function update($restore = false): AbstractModel
    {
        $this->setFormFields($this->getRequestFormFields());

        /** @var Newsletter $newsletter */
        $newsletter = $this->repository->update($this->request->getModel(), $this->processFields(), $restore);

        $this->subscribersGateway->subscribe($newsletter);

        return $newsletter;
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        /** @var Newsletter $newsletter */
        $newsletter = $this->request->getModel();

        $success = $this->repository->destroy($newsletter);
        if ($success) {
            $this->subscribersGateway->unsubscribe($newsletter);
        }

        return $success;
    }

    /**
     * @return array
     */
    private function getRequestFormFields(): array
    {
        return [
            'email' => $this->request->input('email'),
        ];
    }
}