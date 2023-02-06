<?php

namespace App\MailChimp\Services;

use App\MailChimp\Repositories\EventsRepository;
use App\Models\User\User;
use Exception;
use MailchimpMarketing\Api\ListsApi;

/**
 * Class EventsService
 * @package App\MailChimp\Services
 */
class EventsService
{
    /**
     * @var ListsApi
     */
    private ListsApi $gateway;

    /**
     * @var array
     */
    private array $config;

    /**
     * ListRepository constructor.
     *
     * @param ListsApi $gateway
     * @param array    $config
     */
    public function __construct(ListsApi $gateway, array $config)
    {
        $this->gateway = $gateway;
        $this->config  = $config;
    }

    /**
     * @param User $user
     *
     * @return array|null
     * @throws Exception
     */
    public function get(User $user): ?array
    {
        return $this->eventsRepository()->all($user);
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
        return $this->eventsRepository()->trigger($user, $name, $data);
    }

    /**
     * @param string|null $listId
     *
     * @return EventsRepository
     */
    private function eventsRepository(string $listId = null): EventsRepository
    {
        if (empty($listId)) {
            $listId = $this->config['lists']['subscribers'];
        }

        return new EventsRepository($this->gateway, $listId);
    }
}
