<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Page;
use App\Models\PetAttachment;
use App\Models\PetType;
use App\Models\States;
use App\Models\UserPets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class UserController extends Controller
{
    public function home()
    {
        return redirect('/');

        // $pageTitle = 'Home';
        // $sections = Page::where('tempname',$this->activeTemplate)->where('slug','/')->first();
        // return view($this->activeTemplate . 'home', compact('pageTitle','sections'));
    }
    public function dashboard()
    {
        $pageTitle   = 'Dashboard';
        $user      = auth()->guard('user')->user();
        return view('user.dashboard', compact('pageTitle'));
    }

    public function myPets()
    {
        $pageTitle      = "All Pets";
        $userPets       =  UserPets::all();
        $pet_type       =  PetType::where('status',1)->get();
        return view('user.pet.add', compact('pageTitle','userPets','pet_type'));
    }
    public function myAllPets(){
        $user_id        = auth()->guard('user')->user()->id;
        $pageTitle      = "All Pets";
        $userPets       =  UserPets::where('user_id',$user_id)->with('attachments','pettype');
        $userPets       =  $userPets->searchable(['name','age','short_description'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('user.pet.index', compact('pageTitle','userPets'));


    }
    public function deletePet($id)
    {
        $user_id = auth()->guard('user')->user()->id;
        $UserPet = UserPets::where(['id' => $id, 'user_id' => $user_id])->first();
        if($UserPet){
            $petattachment = PetAttachment::where(['pet_id' => $id, 'user_id' => $user_id])->delete();
            $UserPet->delete();
            $notify[] = ['success', 'Pet Deleted successfully.'];
            return back()->withNotify($notify);
        }
        else{
            $notify[] = ['error', 'Pet not Deleted.'];
            return back()->withNotify($notify);
        }
    }
    public function myPetSaved(Request $request)
    {
        $notification       = 'Pet added successfully';
        $user_pets = new UserPets();
        if ($request->hasFile('images')) {
            try
            {
                $user_pets->images = fileUploader($request->images, getFilePath('pets'), getFileSize('pets'), @$user_pets->images);
            }
            catch (\Exception $exp)
            {
                $notify[] = ['error', 'Couldn\'t upload image'];
                return response()->json(['message' => 'not image uploaded '], 301);
            }
        }
        if ($request->hasFile('video')) {
            try
            {
                $user_pets->video = fileUploader($request->video, getFilePath('pets'), getFileSize('pets'), @$user_pets->video);
            }
            catch (\Exception $exp)
            {
                $notify[] = ['error', 'Couldn\'t upload video'];
                return response()->json(['message' => 'not upload video'], 301);
            }
        }
        if ($request->hasFile('previous_record')) {
            try
            {
                $user_pets->previous_record = fileUploader($request->previous_record, getFilePath('pets'), getFileSize('pets'), @$user_pets->previous_record);
            }
            catch (\Exception $exp)
            {
                $notify[] = ['error', 'Couldn\'t Previous Recored'];
                return response()->json(['message' => ' Not Previous Record '], 301);
            }
        }
        $user_pets->user_id                 = auth()->guard('user')->user()->id;
        $user_pets->name                    = $request->name;
        $user_pets->age                     = $request->age;
        $user_pets->short_description       = $request->short_description;
        $user_pets->pet_type_id             = $request->pet_type_id;
        $user_pets->save();
        $notify[] = ['success', 'Pet Record Saved'];
        return back()->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $user = auth()->guard('user')->user();
        return view('user.password', compact('pageTitle', 'user'));
    }

    public function passwordUpdate(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->guard('user')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password do not match !!'];
            return back()->withNotify($notify);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('user.password')->withNotify($notify);
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $user    = auth()->guard('user')->user();
        $countries      = Country::all();
        $cities      = Cities::all();
        $states      = States::all();
        return view('user.info.profile', compact('pageTitle', 'user', 'countries', 'cities', 'states'));
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth()->guard('user')->user();

        if ($request->hasFile('image')) {
            try {
                $user->user_image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $user->user_image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        if($request->username != $user->username){
            $this->validate($request, [
                'username' => 'required|string|max:40|min:2|unique:users,username',
            ]);
            $user->username = $request->username;
        }
        if($request->email != $user->email){
            $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
            ]);
            $user->email = $request->email;
        }
        if($request->name != $user->name){
            $this->validate($request, [
                'name' => 'required|max:255',
            ]);
            $user->name = $request->name;
        }
        $user->phone                = $request->phone;
        $user->user_prefer_language = $request->user_prefer_language;
        $user->gender               = $request->gender;
        $user->user_about           = $request->user_about;
        $user->address              = $request->address;
        $user->country_id           = $request->country_id;
        $user->state_id             = $request->state_id;
        $user->city_id              = $request->city_id;
        $user->postal_code          = $request->postal_code;
        $user->latitude             = $request->latitude;
        $user->longitude            = $request->longitude;

        $user->save();
        $notify[] = ['success', 'Your profile has been updated.'];
        return back()->withNotify($notify);
    }

    public function petdetail($id)
    {
        $pageTitle      = 'Pet Detail';
        $userPet        = UserPets::find($id);
        return view('user.detail',compact('pageTitle','userPet'));
    }
}
