<?php

namespace App\MailChimp\Repositories;

use App\Models\User\User;
use Exception;
use MailchimpMarketing\Api\AutomationsApi;

/**
 * Class AutomationRepository
 * @package App\MailChimp\Repositories
 */
class AutomationRepository
{
    use ApiCall;

    /**
     * @var AutomationsApi
     */
    private AutomationsApi $gateway;

    /**
     * @var string
     */
    private string $workFlowId;

    /**
     * @var string|null
     */
    private ?string $workFlowEmailId;

    /**
     * ListRepository constructor.
     *
     * @param AutomationsApi $gateway
     * @param string         $workFlowId
     * @param string|null    $workFlowEmailId
     */
    public function __construct(AutomationsApi $gateway, string $workFlowId, string $workFlowEmailId = null)
    {
        $this->gateway         = $gateway;
        $this->workFlowId      = $workFlowId;
        $this->workFlowEmailId = $workFlowEmailId;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function all(): array
    {
        $response = $this->makeCall(function () {
            return $this->gateway->getWorkflowEmailSubscriberQueue($this->workFlowId, $this->workFlowEmailId);
        });

        $response->handle();

        return $response->getResponse()->queue;
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
            return $this->gateway->getWorkflowEmailSubscriber(
                $this->workFlowId,
                $this->workFlowEmailId,
                md5($user->email)
            );
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
        $subscriber = $this->get($user);

        return !is_null($subscriber);
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
            $this->gateway->addWorkflowEmailSubscriber(
                $this->workFlowId,
                $this->workFlowEmailId,
                [
                    'email_address' => $user->email
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
            $this->gateway->removeWorkflowEmailSubscriber(
                $this->workFlowId,
                [
                    'email_address' => $user->email
                ]
            );
        });

        $response->handle();

        return $response->isSuccess();
    }
}