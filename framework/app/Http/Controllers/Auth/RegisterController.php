<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        return view('site.auth.register', [
            'email' => $request->input('email', '')
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'              => 'required|string|email|max:100|unique:users',
            'password'           => 'required|string|min:8',
            'subscribed'         => 'boolean',
            recaptchaFieldName() => recaptchaRuleName()
        ]);
    }

    /**
     * @param array $data
     *
     * @return \App\Models\AbstractModel
     * @throws \App\Exceptions\CommonException
     */
    protected function create(array $data)
    {
        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        return $userRepository->store([
            'email'      => $data['email'],
            'password'   => md5($data['password']),
            'subscribed' => isset($data['subscribed']) ? 1 : 0
        ]);
    }
}
