<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Models\UserPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('user.auth.passwords.email', compact('pageTitle'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('users');
    }

    public function sendResetCodeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['Email Not Available']);
        }
        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withInput()->withNotify($notify);
        }
        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withInput()->withNotify($notify);
        }
        $userPasswordReset = UserPasswordReset::where('email', $user->email)->orderby('id', 'Desc')->first();
        $code = verificationCode(6);
        if(!isset($userPasswordReset)){
            $userPasswordReset = [
                'email' => $user->email,
                'token' => $code,
                'status' => 0,
                'created_at' => Carbon::now(),
            ];
            $userPasswordReset = UserPasswordReset::create($userPasswordReset);
        }else{
            $userPasswordReset->update(['token' => $code, 'created_at' => Carbon::now()]);
        }

        $staffIpInfo = getIpInfo();
        $staffBrowser = osBrowser();
        notify($user, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $staffBrowser['os_platform'],
            'browser' => $staffBrowser['browser'],
            'ip' => $staffIpInfo['ip'],
            'time' => $staffIpInfo['time']
        ],['email'],false);

        $email = $user->email;

        session()->forget('pass_res_mail');
        session()->put('pass_res_mail',$email);

        Mail::send(new ResetPassword($userPasswordReset));

        return redirect()->route('user.password.code.verify');
    }

    public function codeVerify(){
        $pageTitle = 'Verify Code';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error','Oops! session expired'];
            return redirect()->route('user.password.reset')->withNotify($notify);
        }
        return view('user.auth.passwords.code_verify', compact('pageTitle','email'));
    }

    public function verifyCode(Request $request)
    {

        if($request->code == null || $request->code ==''){
            return back()->withErrors(['msg'=> 'OTP code is required.']);
        }

        $request->validate([
            'code' => 'required',
        ]);

        $notify[] = ['success', 'You can change your password.'];
        $code = str_replace(' ', '', $request->code);

        $userOtpData = UserPasswordReset::where(['email'=> $request->email, 'token' => $code])->orderBy('id', 'Desc')->first();
        if (!isset($userOtpData)) {
            return back()->withErrors(['msg' => 'Your OTP code is not matched.']);
        }elseif(Carbon::parse($userOtpData->created_at)->addMinutes(2)->isPast()){
            return back()->withErrors(['msg' => 'Your OTP code is expired.']);
        }

        return to_route('user.password.reset.form', $code)->withNotify($notify);
    }
    public function resendEmail($email){

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['Email Not Available']);
        }
        $userPasswordReset = UserPasswordReset::where('email', $user->email)->orderby('id', 'Desc')->first();
        $code = verificationCode(6);
        if(!isset($userPasswordReset)){
            $userPasswordReset = [
                'email' => $user->email,
                'token' => $code,
                'status' => 0,
                'created_at' => Carbon::now(),
            ];
            UserPasswordReset::create($userPasswordReset);
        }else{
            $userPasswordReset->update(['token' => $code, 'created_at' => Carbon::now()]);
        }

        $staffIpInfo = getIpInfo();
        $staffBrowser = osBrowser();
        notify($user, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $staffBrowser['os_platform'],
            'browser' => $staffBrowser['browser'],
            'ip' => $staffIpInfo['ip'],
            'time' => $staffIpInfo['time']
        ],['email'],false);

        $email = $user->email;

        session()->forget('pass_res_mail');
        session()->put('pass_res_mail',$email);

        Mail::send(new ResetPassword($userPasswordReset));

        return redirect()->route('user.password.code.verify');
    }
}
