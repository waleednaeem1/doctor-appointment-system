<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:40|min:2|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'postal_code' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'username' => $request->username,
            'postal_code' => $request->postal_code,
            'state_id' => $request->state_id,
            'city_id'  => $request->city_id,
            'country_id' => $request->country_id,
            'password' => Hash::make($request->password),
            'password_confirmation' => $request->password_confirmation,
        ];
        $user = User::create($data);
        $verificationUrl = URL::signedRoute('verify-email', ['id' => $user->id]);
        Mail::to($user->email)->send(new EmailVerification($data, $verificationUrl));
        if($user->email_verified_at == '' || $user->email_verified_at == null){
            return response()->json(['Success' => True, 'msg' => 'Registered successfully, please check your email for verification before proceeding to login...!'], 200);
        }
    }

    public function checkUsernameAndEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required|string|max:40|min:2|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);
        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        return response()->json(['Success' => true, 'msg' => 'Both fields are clear'], 200);
    }

    public function verifyMail($id){
        $user = User::find($id);
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }
        return response()->json(['Success' => True, 'msg' => 'Your email has been verified. You can now log in.'], 201);
    }

    public function editProfile($userId)
    {
        // $userId = User::select('id','user_image','email','phone','map_location','gender', 'username')->where('id', $userId)->first();
        $userId = User::where('id', $userId)->first();
        return response()->json(['Success' => True, 'user' => $userId], 200);
    }

    public function profileUpdate(Request $request)
    {
        $user = User::find($request->userId);

        // $this->validate($request, [
        //     'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        // ]);

        if ($request->hasFile('image')) {
            try {
                $user->user_image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $user->user_image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        if($request->username != $user->username){
            // $this->validate($request, [
            //     'username' => 'required|string|max:40|min:2|unique:users,username',
            // ]);
            $user->username = $request->username;
        }

        if($request->email != $user->email){
            // $this->validate($request, [
            //     'email' => 'required|email|max:255|unique:users',
            // ]);
            $user->email = $request->email;
        }
        if($request->name != $user->name){
            // $this->validate($request, [
            //     'name' => 'required|max:255',
            // ]);
            $user->name = $request->name;
        }
        $user->phone                = $request->phone;
        $user->postal_code          = $request->postal_code;
        $user->user_prefer_language = $request->user_prefer_language;
        $user->gender               = $request->gender;
        $user->user_about           = $request->user_about;
        $user->country_id           = $request->country_id;
        $user->state_id             = $request->state_id;
        $user->city_id              = $request->city_id;

        $user->save();
        $notify = 'Your profile has been updated.';
        return response()->json(['Success' => True, 'msg' => $notify], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }

        $user = User::find($request->user_id);
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'msg' => 'Old password not match!', 'data' => []], 200);
        }
        if (Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'msg' => 'Password already used please enter new password!', 'data' => []], 200);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['success' => true, 'msg' => 'Password changed successfully.', 'data' => []], 200);
    }
}
