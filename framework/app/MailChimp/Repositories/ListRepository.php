<?php

namespace App\MailChimp\Repositories;

use App\Models\User\User;
use Exception;
use MailchimpMarketing\Api\ListsApi;

/**
 * Class ListRepository
 * @package App\MailChimp\Repositories
 */
class ListRepository
{
    use ApiCall;

    /**
     * @var ListsApi
     */
    private ListsApi $gateway;

    /**
     * ListRepository constructor.
     *
     * @param ListsApi $gateway
     */
    public function __construct(ListsApi $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return array|null
     * @throws Exception
     */
    public function all(): ?array
    {
        $response = $this->makeCall(function () {
            return $this->gateway->getAllLists();
        });

        $response->handle();

        return $response->getResponse()->lists;
    }

    /**
     * @param string $listId
     * @param User   $user
     *
     * @return mixed|null
     * @throws Exception
     */
    public function getSubscriber(string $listId, User $user)
    {
        $response = $this->makeCall(function () use ($listId, $user) {
            return $this->gateway->getListMember($listId, md5($user->email));
        });

        if (!$response->isSuccess() && ($response->getErrorStatus() !== 404)) {
            $response->handle();
        }

        return $response->isSuccess() ? $response->getResponse() : null;
    }

    /**
     * @param string $listId
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function storeSegment(string $listId, string $name)
    {
        $response = $this->makeCall(function () use ($listId, $name) {
            return $this->gateway->createSegment($listId, [
                'name'           => $name,
                'static_segment' => []
            ]);
        });

        $response->handle();

        return $response->getResponse()->lists;
    }

    /**
     * @param string $listId
     * @param string $segmentId
     *
     * @return array|null
     * @throws Exception
     */
    public function getSegmentMembers(string $listId, string $segmentId): ?array
    {
        $response = $this->makeCall(function () use ($listId, $segmentId) {
            return $this->gateway->getSegmentMembersList($listId, $segmentId);
        });

        $response->handle();

        return $response->getResponse()->members;
    }

    /**
     * @param string $listId
     * @param string $segmentId
     * @param User   $user
     *
     * @return mixed
     * @throws Exception
     */
    public function addSegmentMember(string $listId, string $segmentId, User $user)
    {
        $response = $this->makeCall(function () use ($listId, $segmentId, $user) {
            return $this->gateway->createSegmentMember($listId, $segmentId, [
                'email_address' => $user->email
            ]);
        });

        $response->handle();

        return $response->getResponse();
    }

    /**
     * @param string $listId
     * @param string $segmentId
     * @param User   $user
     *
     * @return bool
     * @throws Exception
     */
    public function destroySegmentMember(string $listId, string $segmentId, User $user): bool
    {
        $response = $this->makeCall(function () use ($listId, $segmentId, $user) {
            $this->gateway->removeSegmentMember($listId, $segmentId, md5($user->email));
        });

        $response->handle();

        return $response->isSuccess();
    }
}