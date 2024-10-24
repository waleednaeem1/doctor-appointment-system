<?php
namespace App\Http\Controllers\Api\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

use GrahamCampbell\ResultType\Success;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $error = (object)[];
        $validation = Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validation->fails()){
            return response()->json(['success' => false, 'error' => $validation->messages()], 201);
        }

        $data['user'] = User::where('username', $request->username)->first();
        if($data['user'])
        {
            if(Hash::check($request->password,$data['user']->password) == false){
                return response()->json(['Success' => false, 'msg' => 'Incorect password',], 200);
            }

            if(@$request->device_token && $request->type && Hash::check($request->password,$data['user']->password))
            {
                $this->store_device_token(['device_token'=>$request->device_token, 'type'=>$request->type],$data['user']->id);
            }

            $data['user']->tokens->each(function (PersonalAccessToken $token) {
                $token->delete();
            });

            if($data['user']->email_verified_at != null && $data['user']->email_verified_at != '' && $data['user']->status == Status::ACTIVE){
                $data['msg'] = 'Login successfully.' ;
                $data['idToken'] = $data['user']->createToken('searchAVetToken')->accessToken;
                $data['idToken'] = $data['idToken']->token;
            }else{
                // $data['is_verified'] = false ;
                // $data['msg'] = 'Please verify your account before login or check your account is active.';
                // $data['token'] = null;
                return response()->json(['Success' => false, 'msg' => 'Please verify your account before login or check your account is active.',], 200);

            }


            return response()->json(['userInfo' => $data], 200);
        }else{
            return response()->json(['Success' => false, 'msg' => 'User not found', 'error' => $error], 201);
        }
    }

}
