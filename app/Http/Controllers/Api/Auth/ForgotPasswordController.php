<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserPasswordReset;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    public function sendResetCodeEmail(Request $request)
    {
        if($request->email == null || $request->email == ''){
            return response()->json(['success' => false, 'error' => 'Email not found.'], 201);
        }
        $user = User::where('email', $request->email)->first();
        if(!isset($user->email) || $user->email == ''){
            return response()->json(['success' => false, 'error' => 'Email not found.'], 201);
        }
        $userPasswordReset = UserPasswordReset::where('email', $user->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Email not found.'], 201);
        }

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

        Mail::send(new ResetPassword($userPasswordReset));

        return response()->json(['success' => true, 'user' => $user, 'token' => $code], 200);
    }


    public function verifyCode(Request $request)
    {
        if($request->code == null || $request->code ==''){
            return response()->json(['success' => false, 'error' => 'Pin code is required.'], 201);
        }

        $userOtpData = UserPasswordReset::where(['email'=> $request->email, 'token' => $request->code])->orderBy('id', 'Desc')->first();
        if (!isset($userOtpData)) {
            return response()->json(['Success' => false, 'error' => 'Your OTP code is not matched.'], 201);
        }elseif(Carbon::parse($userOtpData->created_at)->addMinutes(2)->isPast()){
            return response()->json(['Success' => false, 'error' => 'Your OTP code is expired.'], 201);
        }
        return response()->json(['Success' => True, 'email' => $request->email,  'msg' => 'You can change your password'], 200);
    }

    public function changePassword(Request $request){

        $user  = User::where('email', $request->email)->first();
        if(!$user)
        {
            return response()->json(['success' => false, 'msg' => 'Email not found.'], 201);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['Success' => true, 'msg' => 'Password changed successfuly.'], 200);
    }
}
