<?php

namespace App\Common;

use App\Contracts\SubscribersGatewayInterface;
use App\Models\Newsletter;
use Spatie\Newsletter\Newsletter as Client;

/**
 * Class MobileGateway
 * @package App\Common
 */
class SubscribersGateway implements SubscribersGatewayInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * MobileGateway constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Newsletter $newsletter
     *
     * @return SubscribersGateway
     */
    public function subscribe(Newsletter $newsletter): SubscribersGatewayInterface
    {
        $this->client->subscribeOrUpdate($newsletter->email, [], '', ['status' => 'pending']);

        return $this;
    }

    /**
     * @param Newsletter $newsletter
     *
     * @return SubscribersGateway
     */
    public function unsubscribe(Newsletter $newsletter): SubscribersGatewayInterface
    {
        if ($this->isSubscribed($newsletter->email)) {
            $this->client->unsubscribe($newsletter->email);
        }

        return $this;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isSubscribed(string $email): bool
    {
        return $this->client->isSubscribed($email);
    }
}