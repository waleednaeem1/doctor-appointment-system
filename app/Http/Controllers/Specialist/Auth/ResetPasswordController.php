<?php

namespace App\Http\Controllers\Doctor\Auth;

use App\Models\Doctor;
use App\Models\DoctorPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;


class ResetPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/doctor/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('doctor.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $pageTitle  = "Account Recovery";
        $resetToken = DoctorPasswordReset::where('token', $token)->orderBy('id', 'Desc')->first();
        if (!$resetToken) {
            $notify[] = ['error', 'Verification code mismatch'];
            return to_route('doctor.password.reset')->withNotify($notify);
        }
        $email = $resetToken->email;
        $qryUsername = Doctor::where('email',$email)->orderBy('id', 'Desc')->first();
        $getUsername = $qryUsername->username; 
        return view('doctor.auth.passwords.reset', compact('pageTitle', 'email', 'token','getUsername'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $reset = DoctorPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $user  = Doctor::where('email', $reset->email)->first();

        $user->password = bcrypt($request->password);
        $user->save();

        $userIpInfo = getIpInfo();
        $userBrowser = osBrowser();

        notify($user, 'PASS_RESET_DONE', [
            'operating_system' => $userBrowser['os_platform'],
            'browser'          => $userBrowser['browser'],
            'ip'               => $userIpInfo['ip'],
            'time'             => $userIpInfo['time']
        ],['email'],false);

        $notify[] = ['success', 'Password changed'];
        // return to_route('doctor.login')->withNotify($notify);
        return to_route('login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('doctors');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard('doctor');
    }
}
