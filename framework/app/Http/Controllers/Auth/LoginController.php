<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('site.auth.login');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        $this->restoreFakeUser($credentials['email']);

        $user = User::where([
            'email'    => $credentials['email'],
            'password' => md5($credentials['password'])
        ])->first();

        if ($user) {
            $this->guard()->login($user, $request->has('remember'));

            return true;
        }

        return false;
    }

    private function restoreFakeUser($email)
    {
        $result = DB::table('fake_users')
            ->leftJoin('users', 'fake_users.email', '=', 'users.email')
            ->select([
                'fake_users.id',
                'users.id as userId',
            ])
            ->where('fake_users.email', '=', $email)
            ->first();

        if ($result && !$result->userId) {
            try {
                DB::insert('INSERT INTO users SELECT * FROM fake_users WHERE id = ?', [$result->id]);
            } catch (Exception $e) {
                dd($e);
            }
        }
    }
}
