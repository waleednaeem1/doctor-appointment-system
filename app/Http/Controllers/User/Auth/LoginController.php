<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laramin\Utility\Onumoti;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = 'user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if(auth()->guard('user')->user()){
            return redirect('/');
        }
        else{
            $pageTitle = "User Login";
            return view('user.auth.login', compact('pageTitle'));
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('user');
    }

    public function username()
    {
        return 'username';
    }

    
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $request->session()->regenerateToken();
        Onumoti::getData();

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
        $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = [
                'error',
                'Your account is not active. Please contact administrator'
            ];
            
            return back()->withNotify($notify);
        }elseif ($user->email_verified_at == null || $user->email_verified_at == '') {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number,
                'gender' => $user->gender,
                'username' => $user->username,
                'postal_code' => $user->postal_code,
                'password' => $user->password,
            ];
            $this->guard()->logout();
            $verificationUrl = URL::signedRoute('verify-email', ['id' => $user->id]);
            Mail::to($user->email)->send(new EmailVerification($data, $verificationUrl));
            $notify[] = ['error','Your email is not verified. We have resent you a verification email.'];
            return back()->withNotify($notify);
        }
    }
    public function logout(Request $request)
    {
        $this->guard('user')->logout();
        $request->session()->invalidate();
        return $this->loggedOut($request) ?: redirect('/');
    }
}
