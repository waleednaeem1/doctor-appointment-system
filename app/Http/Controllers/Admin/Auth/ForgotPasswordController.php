<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Admin;
use App\Models\AdminPasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
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
        parent::__construct();
        $this->middleware('admin.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $pageTitle = 'Account Recovery';
        return view('admin.auth.passwords.email', compact('pageTitle'));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    public function sendResetCodeEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            return back()->withErrors(['Email Not Available']);
        }

        $AdminPasswordReset = AdminPasswordReset::where('email', $admin->email)->first();
        $code = verificationCode(6);
        if(!isset($AdminPasswordReset)){
            $AdminPasswordReset = [
                'email' => $admin->email,
                'token' => $code,
                'status' => 0,
                'created_at' => Carbon::now(),
            ];
            AdminPasswordReset::create($AdminPasswordReset);
        }else{
            $AdminPasswordReset->update(['token' => $code, 'created_at' => Carbon::now()]);
        }

        $adminIpInfo = getIpInfo();
        $adminBrowser = osBrowser();
        notify($admin, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => $adminBrowser['os_platform'],
            'browser' => $adminBrowser['browser'],
            'ip' => $adminIpInfo['ip'],
            'time' => $adminIpInfo['time']
        ],['email'],false);

        $email = $admin->email;
        
        session()->forget('pass_res_mail');
        session()->put('pass_res_mail',$email);

        Mail::send(new ResetPassword($AdminPasswordReset));

        return to_route('admin.password.code.verify');
    }

    public function codeVerify(){
        $pageTitle = 'Verify Code';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error','Oops! session expired'];
            return to_route('admin.password.reset')->withNotify($notify);
        }
        return view('admin.auth.passwords.code_verify', compact('pageTitle','email'));
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

        $userOtpData = AdminPasswordReset::where(['email'=> $request->email, 'token' => $code])->orderBy('id', 'Desc')->first();
        if (!isset($userOtpData)) {
            return back()->withErrors(['msg' => 'Your OTP code is not matched.']);
        }elseif(Carbon::parse($userOtpData->created_at)->addMinutes(2)->isPast()){
            return back()->withErrors(['msg' => 'Your OTP code is expired.']);
        }

        return to_route('admin.password.reset.form', $code)->withNotify($notify);
    }
}
