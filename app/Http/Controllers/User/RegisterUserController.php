<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\User;
use App\Notifications\UserNeedsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterUserController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:40|min:2|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'address' => 'required',
            // 'country_id' => 'required',
            // 'state_id' => 'required',
            // 'city_id' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withInput()->withNotify($notify);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone_number,
            'gender' => $request->gender,
            'username' => $request->username,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'state_id' => $request->state_id,
            'city_id'  => $request->city_id,
            'latitude'  => $request->latitude,
            'longitude'  => $request->longitude,
            'country_id' => $request->country_id,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($data);
        $verificationUrl = URL::signedRoute('verify-email', ['id' => $user->id]);
        Mail::to($user->email)->send(new EmailVerification($data, $verificationUrl));
        if($user->email_verified_at == '' || $user->email_verified_at == null){
            $message       = 'Registered successfully, please check your email for verification before proceeding to login...!';
            $notify[] = ['success', $message];
            return redirect('/login')->withNotify($notify);
            // return redirect('/login')->withErrors(['msg'=> "Registered successfully, please check your email for verification before proceeding to login...!"]);
        }
    }

    public function verifyMail($id){
        $user = User::find($id);
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }
        return redirect()->route('verify-account');
    }

}
