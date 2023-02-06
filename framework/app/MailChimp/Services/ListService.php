<?php

namespace App\MailChimp\Services;

use App\Assets\MailChimp\AbandonedProduct;
use App\MailChimp\Repositories\ListRepository;
use App\MailChimp\Repositories\UserRepository;
use App\Models\Product\Product;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use MailchimpMarketing\Api\ListsApi;

/**
 * Class ListService
 * @package App\MailChimp\Services
 */
class ListService
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
     * @return array|null
     * @throws Exception
     */
    public function getLists(): ?array
    {
        return $this->listRepository()->all();
    }

    /**
     * @param string $listId
     * @param User   $user
     *
     * @return bool
     * @throws Exception
     */
    public function subscribeUserToList(string $listId, User $user): bool
    {
        $userRepository = $this->userRepository($listId);

        return $userRepository->exists($user)
            ? $userRepository->update($user)
            : $userRepository->store($user);
    }

    /**
     * @param string $listId
     * @param User   $user
     * @param bool   $hardDelete
     *
     * @return bool
     * @throws Exception
     */
    public function unsubscribeUserFromList(string $listId, User $user, bool $hardDelete = false): bool
    {
        $userRepository = $this->userRepository($listId);
        if (!$userRepository->exists($user)) {
            return true;
        }

        return $hardDelete
            ? $userRepository->destroy($user)
            : $userRepository->update($user);
    }

    /**
     * @param string $listId
     *
     * @return bool
     * @throws Exception
     */
    public function updateSubscribers(string $listId): bool
    {
        $userRepository = $this->userRepository($listId);
        $page           = 1;
        $hasUsers       = true;
        $subscribers    = collect([]);

        while ($hasUsers) {
            $users = $userRepository->all($page, 500);
            foreach ($users as $user) {
                $subscribed = $user->status === 'subscribed';
                if (!$subscribed) {
                    continue;
                }

                $subscribers->push($user->email_address);
            }

            $page++;
            $hasUsers = !empty($users);
        }

        $chunks = $subscribers->chunk(100);
        $chunks->each(function ($users) {
            DB::table('users')
                ->whereIn('email', $users)
                ->update(['subscribed' => 1]);
        });

        return true;
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
        return $this->listRepository()->storeSegment($listId, $name);
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
        return $this->listRepository()->getSegmentMembers($listId, $segmentId);
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
        return $this->listRepository()->addSegmentMember($listId, $segmentId, $user);
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
        return $this->listRepository()->destroySegmentMember($listId, $segmentId, $user);
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
        return $this->listRepository()->getSubscriber($listId, $user);
    }

    /**
     * @param string  $listId
     * @param User    $user
     * @param Product $product
     *
     * @return bool
     * @throws Exception
     */
    public function saveAbandonedData(string $listId, User $user, Product $product): bool
    {
        $userRepository = $this->userRepository($listId);
        if (!$userRepository->exists($user)) {
            return false;
        }

        return $userRepository->saveAbandonedData($user, AbandonedProduct::make($product));
    }

    /**
     * @return ListRepository
     */
    private function listRepository(): ListRepository
    {
        return new ListRepository($this->gateway);
    }

    /**
     * @param string $listId
     *
     * @return UserRepository
     */
    private function userRepository(string $listId): UserRepository
    {
        return new UserRepository($this->gateway, $listId);
    }
}
