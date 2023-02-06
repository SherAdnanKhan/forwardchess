<?php

namespace App\MailChimp\Services;

use App\MailChimp\Repositories\AutomationRepository;
use App\Models\User\User;
use Exception;
use MailchimpMarketing\Api\AutomationsApi;

/**
 * Class AutomationService
 * @package App\MailChimp\Services
 */
class AutomationService
{
    /**
     * @var ListService
     */
    private ListService $listService;

    /**
     * @var EventsService
     */
    private EventsService $eventsService;

    /**
     * @var AutomationsApi
     */
    private AutomationsApi $gateway;

    /**
     * @var array
     */
    private array $config;

    /**
     * ListRepository constructor.
     *
     * @param ListService    $listService
     * @param EventsService  $eventsService
     * @param AutomationsApi $gateway
     * @param array          $config
     */
    public function __construct(ListService $listService, EventsService $eventsService, AutomationsApi $gateway, array $config)
    {
        $this->listService   = $listService;
        $this->eventsService = $eventsService;
        $this->gateway       = $gateway;
        $this->config        = $config;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function all(): array
    {
        return $this->automationRepository()->all();
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function get(User $user): bool
    {
        return $this->automationRepository()->get($user);
    }

    /**
     * @param User  $user
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function store(User $user): bool
    {
        if ($this->automationRepository()->exists($user)) {
            return true;
        }

        $isSubscribed = $this->listService->getSubscriber($this->getListId(), $user);
        if (empty($isSubscribed)) {
            return false;
        }

        $this->eventsService->trigger($user, 'BrowseAbandon');
        $this->listService->addSegmentMember($this->getListId(), $this->getSegmentId(), $user);

        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws Exception
     */
    public function destroy(User $user): bool
    {
        if (!$this->automationRepository()->exists($user)) {
            return true;
        }

        return $this->listService->destroySegmentMember($this->getListId(), $this->getSegmentId(), $user);
    }

    private function getListId(): string
    {
        return $this->config['lists']['subscribers'];
    }

    private function getSegmentId(): string
    {
        return $this->config['automations']['browseAbandoned']['segmentId'];
    }

    /**
     * @return AutomationRepository
     */
    private function automationRepository(): AutomationRepository
    {
        return new AutomationRepository(
            $this->gateway,
            $this->config['automations']['browseAbandoned']['id'],
            $this->config['automations']['browseAbandoned']['emailId'],
        );
    }

    /**
     * @throws Exception
     */
    private function createSegment()
    {
        $this->listService->storeSegment($this->getListId(), 'BrowseAbandoned');
    }
}
