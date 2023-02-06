<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Repositories\User\UserRepository;

/**
 * Class UpdateUserListener
 * @package App\Listeners
 */
class UpdateUserListener
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UpdateUserListener constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param OrderCompletedEvent $event
     *
     * @throws \App\Exceptions\CommonException
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->getOrder();

        $attributes = [
            'firstName',
            'lastName',
            'city',
            'state',
            'country'
        ];

        $billing = $order->billing;
        $user    = $order->user;

        $user->firstName = null;
        $user->lastName  = null;
        $user->city      = null;

        $data = [];
        foreach ($attributes as $name) {
            $userValue  = $user->getAttribute($name);
            $orderValue = $billing->getAttribute($name);

            if (is_null($userValue) && !is_null($orderValue)) {
                $data[$name] = $orderValue;
            }
        }

        if (is_null($user->address)) {
            $data['address'] = trim("{$billing->address1} {$billing->address2}");
        }

        if (!empty($attributes)) {
            $this->userRepository->update($user, $data);
        }
    }
}
