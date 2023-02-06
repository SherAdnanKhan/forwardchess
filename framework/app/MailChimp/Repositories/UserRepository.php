<?php

namespace App\MailChimp\Repositories;

use App\Assets\MailChimp\AbandonedProduct;
use App\Models\User\User;
use Exception;
use MailchimpMarketing\Api\ListsApi;

/**
 * Class UserRepository
 * @package App\MailChimp\Repositories
 */
class UserRepository
{
    use ApiCall;

    /**
     * @var ListsApi
     */
    private ListsApi $gateway;

    /**
     * @var string
     */
    private string $listId;

    /**
     * UserRepository constructor.
     *
     * @param ListsApi $gateway
     * @param string   $listId
     */
    public function __construct(ListsApi $gateway, string $listId)
    {
        $this->gateway = $gateway;
        $this->listId  = $listId;
    }

    /**
     * @param int $page
     * @param int $rowsPerPage
     *
     * @return array
     * @throws Exception
     */
    public function all($page = 1, $rowsPerPage = 10): array
    {
        $response = $this->makeCall(function () use ($page, $rowsPerPage) {
            return $this->gateway->getListMembersInfo($this->listId, null, null, $rowsPerPage, ($page - 1) * $rowsPerPage);
        });

        $response->handle();

        return $response->getResponse()->members;
    }

    /**
     * @param User $user
     *
     * @return mixed
     * @throws Exception
     */
    public function get(User $user)
    {
        $response = $this->makeCall(function () use ($user) {
            return $this->gateway->getListMember($this->listId, md5($user->email));
        });

        if (!$response->isSuccess() && ($response->getErrorStatus() !== 404)) {
            $response->handle();
        }

        return $response->isSuccess() ? $response->getResponse() : null;
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function exists(User $user): bool
    {
        $user = $this->get($user);

        return !is_null($user);
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function store(User $user): bool
    {
        $response = $this->makeCall(function () use ($user) {
            return $this->gateway->addListMember(
                $this->listId,
                [
                    'email_address' => $user->email,
                    'status'        => 'subscribed',
                    'merge_fields'  => [
                        'FNAME' => $user->firstName ?: '',
                        'LNAME' => $user->lastName ?: '',
                        'PHONE' => $user->mobile ?: '',
                    ]
                ]
            );
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function update(User $user): bool
    {
        $response = $this->makeCall(function () use ($user) {
            return $this->gateway->updateListMember(
                $this->listId,
                md5($user->email),
                [
                    'email_address' => $user->email,
                    'status'        => $user->subscribed ? 'subscribed' : 'unsubscribed',
                    'merge_fields'  => [
                        'FNAME' => $user->firstName ?: '',
                        'LNAME' => $user->lastName ?: '',
                        'PHONE' => $user->mobile ?: '',
                    ]
                ]
            );
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param User             $user
     * @param AbandonedProduct $product
     *
     * @return bool
     * @throws Exception
     */
    public function saveAbandonedData(User $user, AbandonedProduct $product): bool
    {
        $response = $this->makeCall(function () use ($user, $product) {
            return $this->gateway->updateListMember(
                $this->listId,
                md5($user->email),
                [
                    'merge_fields' => $product->toArray()
                ]
            );
        });

        $response->handle();

        return $response->isSuccess();
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(User $user): bool
    {
        $response = $this->makeCall(function () use ($user) {
            $this->gateway->deleteListMemberPermanent(
                $this->listId,
                md5($user->email)
            );
        });

        $response->handle();

        return $response->isSuccess();
    }
}