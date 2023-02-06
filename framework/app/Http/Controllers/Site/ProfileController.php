<?php

namespace App\Http\Controllers\Site;

use App\Assets\SortBy;
use App\Exceptions\CommonException;
use App\Models\User\User;
use App\Repositories\User\UserRepository;
use App\Rules\MobileRule;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Site
 */
class ProfileController extends AbstractSiteController
{
    /**
     * @param OrderService $orderService
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(OrderService $orderService)
    {
        /** @var User $user */
        $user   = Auth::user();
        // $orders = $orderService->paginate(['userId' => $user->id], 5, SortBy::make('created_at', 'desc'));
        $orders =  collect($orderService->getOrderFromMobile($user->email));

        $this->addViewData([
            'orders'  => $orders,
            'profile' => $user->toJson()
        ]);

        return view('site.pages.profile', $this->viewData);
    }

    /**
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     * @throws CommonException
     * @throws ValidationException
     */
    public function changePass(Request $request, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = Auth::user();

        $validationRules = [
            'currentPassword' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (md5($value) !== $user->password) {
                        $fail('Current password is invalid.');
                    }
                }
            ],

            'password' => 'required|string|min:8|confirmed',
        ];

        Validator::make($request->all(), $validationRules)->validate();

        $userRepository->update($user, [
            'password' => md5($request->input('password'))
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * @param Request        $request
     * @param UserRepository $userRepository
     *
     * @return JsonResponse
     * @throws CommonException
     * @throws ValidationException
     */
    public function changeMobile(Request $request, UserRepository $userRepository)
    {
        /** @var User $user */
        $user = Auth::user();

        $validationRules = [
            'mobile' => ['required', 'string', 'min:8', Rule::unique('users')->ignore($user->id), new MobileRule]
        ];

        Validator::make($request->all(), $validationRules)->validate();

        $userRepository->update($user, [
            'mobile' => $request->input('mobile')
        ]);

        return response()->json(['success' => true]);
    }
}