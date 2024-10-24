<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\Doctor;
use App\Models\User;
use App\Notifications\UserNeedsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterDoctorController extends Controller
{
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:40|min:6|unique:doctors,username',
            'email' => 'required|string|email|max:255|unique:doctors,email',
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
            'mobile' => $request->phone_number,
            'gender' => $request->gender,
            'username' => $request->username,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'password' => Hash::make($request->password),
            'state_id' => $request->state_id,
            'city_id'  => $request->city_id,
            'country_id' => $request->country_id,
            'item_lat'  => $request->latitude,
            'item_lng'  => $request->longitude,
            'status'=> 0
        ];
        $user = Doctor::create($data);
        $verificationUrl = URL::signedRoute('doctor-verify-email', ['id' => $user->id]);
        Mail::to($user->email)->send(new EmailVerification($data, $verificationUrl));
        if($user->email_verified_at == '' || $user->email_verified_at == null){
            //$message       = 'Doctor Registered successfully';
            $message = 'Registered successfully, Please Contact Admin to get Approval of account';
            $notify[] = ['success', $message];
            return redirect('/login')->withNotify($notify);
            // return redirect('/login')->withErrors(['msg'=> "Registered successfully"]);
       }
    }

    public function verifyMail($id){
        $doctor = Doctor::find($id);
        if ($doctor) {
            $doctor->email_verified_at = now();
            $doctor->save();
        }
        return redirect()->route('verify-account');
    }

}
