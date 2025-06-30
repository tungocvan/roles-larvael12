<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'username';
    }
    protected function login(Request $request)
    {
        //dd($request);
        $this->validateLogin($request);
        $username = $request->username;
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $username)->first();

            if (!$user) {
                throw ValidationException::withMessages([
                    'username' => 'Email không tồn tại trong hệ thống.',
                ]);
            }

            $username = $user->username;
        }

        if (Auth::attempt(['username' => $username, 'password' => $request->password])) {
            // Authentication was successful...
            $request->session()->regenerate();
            return redirect()->intended('home');
        }else{
                throw ValidationException::withMessages([
                    'username' => 'user hoặc mật khẩu không đúng.',
                ]);
        }



    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }



}
