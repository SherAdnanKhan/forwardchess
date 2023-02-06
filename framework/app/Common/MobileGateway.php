<?php

namespace App\Common;

use App\Assets\MobileRequest;
use App\Contracts\MobileGatewayInterface;
use App\Models\Order\Order;
use App\Models\User\User;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Session;

/**
 * Class MobileGateway
 * @package App\Common
 */
class MobileGateway implements MobileGatewayInterface
{
    /**
     * @var HttpClient
     */
    private HttpClient $client;

    /**
     * MobileGateway constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;

        // https://api.forwardchess.com/v2/api-docs
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws GuzzleException
     */
    public function registerAccount(User $user): bool
    {
        $this->makeCall(MobileRequest::make('POST', 'accounts', [
            'email'     => $user->email,
            'firstName' => $user->firstName,
            'lastName'  => $user->lastName,
            'password'  => $user->password,
            'mobile'    => $user->mobile,
            'verified'  => !empty($user->email_verified_at)
        ]));

        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     * @throws GuzzleException
     */
    public function updateAccount(User $user): bool
    {
        try {
            $this->registerAccount($user);
        } catch (\Exception $e) {
            $this->makeCall(MobileRequest::make('POST', 'accounts/' . $user->email, [
                'email'     => $user->email,
                'firstName' => $user->firstName,
                'lastName'  => $user->lastName,
                'password'  => $user->password,
                'mobile'    => $user->mobile,
                'verified'  => !empty($user->email_verified_at)
            ]));
        }

        return true;
    }

    /**
     * @param Order $order
     *
     * @return bool
     * @throws GuzzleException
     */
    public function registerWebPurchase(Order $order): bool
    {
        $products = [];
        foreach ($order->items as $item) {
            if ($item->product && !$item->product->isBundle) {
                $products[] = $item->product->sku;
            }
        }

        if (!empty($products)) {
            $this->makeCall(MobileRequest::make('POST', 'purchases', [
                'transactionId' => $order->id,
                'amount'        => $order->total,
                'email'         => $order->user->email,
                'products'      => $products,
            ]));
        }

        return true;
    }

    /**
     * @param array $options
     *
     * @return string
     * @throws GuzzleException
     */
    public function sendMail(array $options): string
    {
        $options = [
            'headers' => [
                'X-Authorization' => env('MOBILE_SECRET'),
                'Content-Type'    => 'application/json',
                'Accept'          => 'application/json'
            ],
            'json'    => $options
        ];

        $response = $this->client->request('POST', 'https://forwardchessbackend.appspot.com/emails', $options);

        return $response->getBody()->getContents();
    }

    /**
     * @param MobileRequest $request
     *
     * @return string
     * @throws GuzzleException
     */
    private function makeCall(MobileRequest $request)
    {
        $options = [
            'headers' => [
                'Authorization' => env('MOBILE_SECRET'),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json'
            ],
            'json'    => $request->getParams()
        ];

        $response = $this->client->request($request->getMethod(), $request->getUrl(), $options);

        return $response->getBody()->getContents();
    }

    /**
     * @param Order $order
     *
     * @return bool
     * @throws GuzzleException
     */
    public function getMobilePurchase($email): array
    {
        // fix this
        if (!empty($email)) {
            $data = $this->makeCall(MobileRequest::make('GET', 'all-purchases/' . $email));
        }

        return json_decode($data, true);
    }

    /**
     * @param array $skuList
     *
     * @return array
     * @throws GuzzleException
     */
    public function getRecommendedBooks($skuList): array
    {
        $data = $this->makeCall(MobileRequest::make('POST','similar-books/', $skuList));

        return json_decode($data, true);
    }

    /**
     * @param string $productSku
     * @return array
     * @throws GuzzleException
     */
    public function getReviews(string $productSku): array
    {
        $data = $this->makeCall(MobileRequest::make('GET', 'books/'. $productSku . '/reviews'));

        return json_decode($data, true);
    }

    /**
     * @param array $fields
     * @return array
     * @throws GuzzleException
     */
    public function postReview(array $fields): array
    {
        $data = $this->makeCall(MobileRequest::make('POST', 'books/reviews/', $fields));

        return json_decode($data, true);

    }

    /**
     * @param string $email
     * @return bool
     * @throws GuzzleException
     */
    public function getFirebaseToken(string $email): bool
    {
        try {
            $response = $this->makeCall(MobileRequest::make('POST', 'tokens', [
                'email'  => $email,
                'secret' => uniqid(),
            ]));

            $data['token'] = json_decode($response, true)['token'];
            $data['expire_at'] = now();
            Session::put('firebase_token', $data);
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
