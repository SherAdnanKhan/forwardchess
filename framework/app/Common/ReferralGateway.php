<?php

namespace App\Common;

use App\Contracts\ReferralGatewayInterface;
use App\Models\Order\Order;
use GuzzleHttp\Client as HttpClient;
use Request;

/**
 * Class ReferralGateway
 * @package App\Common
 */
class ReferralGateway implements ReferralGatewayInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * MobileGateway constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Order $order
     * @param array $data
     *
     * @return bool
     */
    public function purchase(Order $order, array $data = []): bool
    {
        if (env('REFERRAL_ENABLED')) {
            // hack. still investigating the problem
            $total = explode('.', $order->total);
            if (count($total) === 2 && strlen($total[1]) > 2) {
                $total[1] = substr($total[1], 0, 2);
            }

            $total = implode('.', $total);

            $this->makeCall([
                'first_name'      => $order->billing->firstName,
                'last_name'       => $order->billing->lastName,
                'email'           => $order->user->email,
                'discount_code'   => $order->coupon,
                'order_timestamp' => $order->created_at->timestamp,
                'browser_ip'      => $data['ip'],
                'user_agent'      => $data['userAgent'],
                'invoice_amount'  => $total,
                'currency_code'   => 'USD',
                'locale'          => 'en',
            ]);
        }

        return true;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function makeCall(array $params = [])
    {
        $params = array_merge([
            'timestamp' => time(),
            'accessID'  => env('REFERRAL_ACCESS_ID')
        ], $params);

        $params['signature'] = $this->makeSignature($params);

        $response = $this->client->post(env('REFERRAL_BASE_URL') . 'purchase.json', [
            'json' => $params
        ]);

//        Log::debug($params);
        return $response->getBody()->getContents();
    }

    private function makeSignature(array $params): string
    {
        uksort($params, function ($a, $b) {
            return $a <=> $b;
        });

        $signature = env('REFERRAL_SECRET_KEY');
        foreach ($params as $key => $value) {
            $signature .= $key . '=' . $value;
        }

        return md5($signature);
    }
}