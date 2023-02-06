<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\CommonException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

/**
 * Class MobileController
 * @package App\Http\Controllers\Api
 */
class MobileController extends Controller
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * MobileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param bool    $store
     *
     * @return array
     */
    private function validateUser(Request $request, bool $store = false): array
    {
        $rules = [
            'email'     => 'required|email|max:100' . ($store ? '|unique:users,email' : ''),
            'password'  => 'required|string|max:250',
            'firstName' => 'required|max:150',
            'lastName'  => 'required|max:150',
        ];

        $request->validate($rules);

        return [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
            'firstName' => $request->input('firstName'),
            'lastName'  => $request->input('lastName'),
            'active'    => 1,
        ];
    }

    /**
     * @param string $email
     *
     * @return User|null
     * @throws ResourceNotFoundException
     */
    private function findUserByEmail(string $email): ?User
    {
        /** @var User $user */
        $user = $this->userRepository->first(['email' => $email]);
        if (empty($user)) {
            throw new ResourceNotFoundException('User not found!');
        }

        return $user;
    }

    /**
     * @param Request $request
     *
     * @return UserResource
     * @throws CommonException
     */
    public function createAccount(Request $request): UserResource
    {
        $fields = $this->validateUser($request, true);

        if ($request->input('verified')) {
            $fields['email_verified_at'] = Carbon::now();
        }

        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = User::getEventDispatcher();

        // disabling the events
        User::unsetEventDispatcher();

        /** @var User $user */
        $user = $this->userRepository->store($fields);

        event(new Registered($user));

        // enabling the event dispatcher
        User::setEventDispatcher($dispatcher);

        return UserResource::make($user);
    }

    /**
     * @param Request $request
     *
     * @return UserResource
     * @throws ResourceNotFoundException
     * @throws CommonException
     */
    public function updateAccount(Request $request): UserResource
    {
        $fields = $this->validateUser($request);
        $email  = $fields['email'];
        unset($fields['email']);

        $user = $this->findUserByEmail($email);
        if (empty($user->email_verified_at) && $request->input('verified')) {
            $fields['email_verified_at'] = Carbon::now();
        }

        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = User::getEventDispatcher();

        // disabling the events
        User::unsetEventDispatcher();

        $user = $this->userRepository->update($user, $fields);

        // enabling the event dispatcher
        User::setEventDispatcher($dispatcher);

        return UserResource::make($user);
    }

//    /**
//     * @param Request           $request
//     * @param OrderRepository   $orderRepository
//     * @param ProductRepository $productRepository
//     * @param                   $email
//     *
//     * @return OrderResource
//     * @throws ResourceNotFoundException
//     * @throws \App\Exceptions\CommonException
//     */
//    public function placeOrder(Request $request, OrderRepository $orderRepository, ProductRepository $productRepository, $email)
//    {
//        $user = $this->findUserByEmail($email);
//
//        $request->validate([
//            'amount'   => 'required|numeric',
//            'products' => 'required|array',
//        ]);
//
//        $amount = toIntAmount($request->input('amount'));
//
//        $orderDetails = [
//            'userId'        => $user->id,
//            'status'        => Order::STATUS_COMPLETED,
//            'subTotal'      => $amount,
//            'total'         => $amount,
//            'allowDownload' => true,
//            'ipAddress'     => $request->ip(),
//            'userAgent'     => $request->userAgent(),
//        ];
//
//        $billingDetails = [
//            'firstName' => $user->firstName,
//            'lastName'  => $user->lastName,
//            'email'     => $user->email,
//        ];
//
//        $items = [];
//        foreach ($request->input('products') as $sku) {
//            /** @var Product $product */
//            $product = $productRepository->first(['sku' => $sku]);
//            if (empty($product)) {
//                throw new ResourceNotFoundException('Product ' . $sku . ' not found!');
//            }
//
//            $items[] = Item::make($product);
//        }
//
//        // getting the dispatcher instance (needed to enable again the event observer later on)
//        $dispatcher = Order::getEventDispatcher();
//
//        // disabling the events
//        Order::unsetEventDispatcher();
//
//        $order = $orderRepository->placeOrder($orderDetails, $items, $billingDetails);
//
//        // enabling the event dispatcher
//        Order::setEventDispatcher($dispatcher);
//
//        return OrderResource::make($order);
//    }
}
