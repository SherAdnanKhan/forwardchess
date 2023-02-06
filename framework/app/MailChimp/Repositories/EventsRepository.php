<?php

namespace App\MailChimp\Repositories;

use App\Models\User\User;
use Carbon\Carbon;
use Exception;
use MailchimpMarketing\Api\ListsApi;

/**
 * Class EventsRepository
 * @package App\MailChimp\Repositories
 */
class EventsRepository
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
     * ListRepository constructor.
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
     * @param User $user
     * @param int  $page
     * @param int  $rowsPerPage
     *
     * @return array
     * @throws Exception
     */
    public function all(User $user, $page = 1, $rowsPerPage = 10): array
    {
        $response = $this->makeCall(function () use ($user, $page, $rowsPerPage) {
            return $this->gateway->getListMemberEvents($this->listId, md5($user->email), $rowsPerPage, ($page - 1) * $rowsPerPage);
        });

        if (!$response->isSuccess() && ($response->getErrorStatus() !== 404)) {
            $response->handle();
        }

        return $response->getResponse()->events;
    }

    /**
     * @param User   $user
     * @param string $name
     * @param array  $data
     *
     * @return bool
     * @throws Exception
     */
    public function trigger(User $user, string $name, array $data = []): bool
    {
        $response = $this->makeCall(function () use ($user, $name, $data) {
            $this->gateway->createListMemberEvent($this->listId, md5($user->email), [
                'name'        => $name,
                'occurred_at' => Carbon::now()->toIso8601String(),
                'properties'  => $data
            ]);
        });

        $response->handle();

        return $response->isSuccess();
    }
}