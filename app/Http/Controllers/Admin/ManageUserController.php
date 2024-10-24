<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Deposit;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\Department;
use App\Models\DoctorLogin;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Users';
        $users = $this->commonQuery()->paginate(getPaginate());
        return view('admin.user.index', compact('pageTitle', 'users'));
    }



    protected function commonQuery()
    {
        return User::orderBy('id', 'DESC')->searchable(['name', 'mobile', 'email', 'department:name', 'location:name'])->filter(['status']);
    }

    public function status($id)
    {
        return Doctor::changeStatus($id);
    }

    public function featured($id)
    {
        return Doctor::changeStatus($id, 'featured');
    }



    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        if ($id) {
            $user               = User::findOrFail($id);
            $notification       = 'User updated successfully';
        } else {
            $user               = new User();
            $notification       = 'User added successfully';
        }

        $this->userSave($user, $request);



        $notify[] = ['success', $notification];
        return to_route('admin.user.detail', $user->id)->withNotify($notify);
    }

    protected function validation($request, $id = 0)
    {
        $imageValidation                = $id ? 'nullable' : 'required';
        $passwordValidation             = $request->password ? 'confirmed' : '';

        $request->validate([
            'image'         => ["$imageValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'name'          => 'required|string|max:40',
            'username'      => 'required|string|max:40|min:6|unique:users,username,' . $id,
            'email'         => 'required|email|string|unique:users,email,' . $id,
            'phone'         => 'required',
            'password'      => "$passwordValidation"

        ]);
    }

    protected function userSave($user, $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = $user->user_image;
                $user->user_image = fileUploader($request->image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }


        $general = gs();
        $phone = $general->country_code . $request->phone;
        //Email to SuperAdmin
        // $password = passwordGen();
        // $dataAdmin = auth('admin')->user();
        // $staffIpInfo = getIpInfo();
        // $staffBrowser = osBrowser();
        // notify($dataAdmin, 'PASS_RESET_CODE', [
        //     'code' => $password,
        //     'operating_system' => $staffBrowser['os_platform'],
        //     'browser' => $staffBrowser['browser'],
        //     'ip' => $staffIpInfo['ip'],
        //     'time' => $staffIpInfo['time']
        // ],['email'],false);
        //End EmailSuperAdmin

        $data = $request->all();

        //$checkEmail = Doctor::newDoctor($data, $password);


        $user->name               = $request->name;
        $user->username           = $request->username;
        $user->email              = strtolower(trim($request->email));
        $user->phone              = $request->phone;
        $user->gender             = $request->gender;
        $user->status             = $request->status;

        if(isset($request->email_verified_at) && $request->email_verified_at == 'on'){
            $user->email_verified_at = now();
        }else{
            $user->email_verified_at = null;
        }

        if(isset($request->password)){

        $password = trim($request->password);
        $password = Hash::make($password);
        $user->password = $password;

        }


        $user->save();
    }

    public function detail($id)
    {
        $user              = User::findOrFail($id);
        $pageTitle         = 'User Detail - ' . $user->name;
        return view('admin.user.details', compact('pageTitle', 'user'));
    }


    public function login($id)
    {
        $doctor = Doctor::findOrFail($id);
        Auth::guard('doctor')->login($doctor);
        return to_route('doctor.dashboard');
    }

    public function notificationLog($id)
    {

        $doctor    = Doctor::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $doctor->username;
        $logs      = NotificationLog::where('doctor_id', $id)->with('doctor')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.doctor.notification_history', compact('pageTitle', 'logs', 'doctor'));
    }

    public function showNotificationSingleForm($id)
    {
        $doctor = Doctor::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.admin.detail', $doctor->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $doctor->username;
        return view('admin.doctor.notification_single', compact('pageTitle', 'doctor'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $doctor = Doctor::findOrFail($id);
        notify($doctor, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return to_route('admin.doctor.notification.log', $doctor->id)->withNotify($notify);
    }


    public function showNotificationAllForm()
    {

        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $doctors = Doctor::active()->count();
        $pageTitle = 'Notification to Verified Doctors';
        return view('admin.doctor.notification_all', compact('pageTitle', 'doctors'));
    }


    public function sendNotificationAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $doctor = Doctor::active()->skip($request->skip)->first();

        if (!$doctor) {
            return response()->json([
                'error' => 'Doctor not found',
                'total_sent' => 0,
            ]);
        }

        notify($doctor, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }

    public function loginHistory($id = 0)
    {
        $logs      = DoctorLogin::orderByDesc('id')->searchable(['doctor:username, name'])->with('doctor');
        if ($id) {
            $doctor = Doctor::find($id);
            $pageTitle = $doctor->name. ' '.'Login History';
            $loginLogs = $logs->where('doctor_id', $id)->paginate(getPaginate());
        } else {
            $pageTitle = 'Doctor Login History';
            $loginLogs = $logs->paginate(getPaginate());
        }
        return view('admin.doctor.logins', compact('pageTitle', 'loginLogs'));
    }
}
