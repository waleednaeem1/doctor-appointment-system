<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Department;
use App\Models\Deposit;
use App\Models\Doctor;
use App\Models\DoctorLogin;
use App\Models\Location;
use App\Models\NotificationLog;
use App\Models\VetReviews;
use App\Models\PetDisease;
use App\Models\PetType;
use App\Models\States;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManageDoctorsController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Doctors';
        $doctors = $this->commonQuery()->paginate(getPaginate());
        return view('admin.doctor.index', compact('pageTitle', 'doctors'));
    }

    public function active($status)
    {
        $pageTitle = 'Active Doctors';
        $doctors = $this->commonQuery()->where('status', Status::ACTIVE)->paginate(getPaginate());
        return view('admin.doctor.index', compact('pageTitle', 'doctors'));
    }

    public function inactive($status)
    {
        $pageTitle = 'Inactive Doctors';
        $doctors = $this->commonQuery()->where('status', Status::INACTIVE)->paginate(getPaginate());
        return view('admin.doctor.index', compact('pageTitle', 'doctors'));
    }

    protected function commonQuery()
    {
        return Doctor::orderBy('id', 'DESC')->searchable(['name', 'mobile', 'email', 'department:name', 'location:name'])->with('department', 'location')->filter(['status']);
    }

    public function status($id)
    {
        return Doctor::changeStatus($id);
    }

    public function featured($id)
    {
        return Doctor::changeStatus($id, 'featured');
    }

    public function verify($id)
    {
        $query = Doctor::findOrFail($id);
        if($query->email_verified_at == null || $query->email_verified_at == ''){
            $query->email_verified_at = Carbon::now();
        }else{
            $query->email_verified_at = Carbon::now();
        }
        $message       = 'Email verified successfully';
        $query->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function unVerify($id)
    {
        $query = Doctor::findOrFail($id);
        if($query->email_verified_at != null || $query->email_verified_at != ''){
            $query->email_verified_at = null;
        }else{
            $query->email_verified_at = null;
        }
        $message       = 'Email Unverified successfully';
        $query->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function form()
    {
        $pageTitle   = 'Add New Doctor';
        $departments = Department::orderBy('name')->get();
        $petType     = PetType::orderBy('name')->get();
        $locations   = Location::orderBy('name')->get();
        $countries   = Country::where('id',233)->first();
        $states      = States::where('country_id',233)->get();
        $cities      = Cities::all();
        $categories  = Category::all();
        $PetDisease  = PetDisease::all();
        return view('admin.doctor.form', compact('pageTitle', 'departments', 'locations','countries','states','cities','categories','petType','PetDisease'));
    }

    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        if ($id) {
            $doctor         = Doctor::findOrFail($id);
            $notification       = 'Doctor updated successfully';
        } else {
            $doctor         = new Doctor();
            $notification       = 'Doctor added successfully';
        }

        $this->doctorSave($doctor, $request);

        if (!$id) {
            $general = gs();
            notify($doctor, 'PEOPLE_CREDENTIAL', [
                'site_name' => $general->site_name,
                'name'      => $doctor->name,
                'username'  => $doctor->username,
                'password'  => $doctor->password,
                'guard'     => route('login'),
            ]);
        }

        $notify[] = ['success', $notification];
        return to_route('admin.doctor.detail', $doctor->id)->withNotify($notify);
    }

    protected function validation($request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $passwordValidation             = $request->password ? 'confirmed' : '';
        $request->validate([
            'image'         => ["$imageValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'name'          => 'required|string|max:40',
            'username'      => 'required|string|max:40|min:6|unique:doctors,username,' . $id,
            'email'         => 'required|email|string|unique:doctors,email,' . $id,
            //'mobile'        => 'required|numeric|unique:doctors,mobile,' . $id,
            //'department'    => 'required||numeric|gt:0',
            //'location'      => 'required||numeric|gt:0',
            // 'fees'          => 'required|numeric|gt:0',
            'qualification' => 'required|string|max:255',
            'address'       => 'required|string|max:255',
            'about'         => 'required|string|max:500',
            'password'      =>  $passwordValidation
        ]);
    }

    protected function doctorSave($doctor, $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = $doctor->image;
                $doctor->image = fileUploader($request->image, getFilePath('doctorProfile'), getFileSize('doctorProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $general = gs();
        $mobile = $general->country_code . $request->mobile;
        //Email to SuperAdmin
        $password = passwordGen();
        $dataAdmin = auth('admin')->user();
        $staffIpInfo = getIpInfo();
        $staffBrowser = osBrowser();
        notify($dataAdmin, 'PASS_RESET_CODE', [
            'code' => $password,
            'operating_system' => $staffBrowser['os_platform'],
            'browser' => $staffBrowser['browser'],
            'ip' => $staffIpInfo['ip'],
            'time' => $staffIpInfo['time']
        ],['email'],false);
        //End EmailSuperAdmin

        $data = $request->all();
        //$checkEmail = Doctor::newDoctor($data, $password);
        $doctor->name               = $request->name;
        $doctor->username           = $request->username;
        $doctor->email              = strtolower(trim($request->email));
        $doctor->password           = Hash::make($password);
        $doctor->mobile             = $mobile;
        $doctor->department_id      = implode(",",$request->department);
        // $doctor->pet_type_id        = implode(",",$request->pet_type);
        //$doctor->pet_disese_id      = implode(",",$request->pet_disese_id);
        $doctor->location_id        = 0;
        $doctor->qualification      = $request->qualification;
        // $doctor->fees               = $request->fees;
        $doctor->address            = $request->address;
        $doctor->about              = $request->about;
        //start new fields
        $doctor->country_id = $request->country_id;
        // $doctor->country_id = 233;
        $doctor->state_id = $request->state_id;
        $doctor->city_id = $request->city_id;
        $doctor->item_postal_code = $request->postal_code;
        $doctor->item_lat = $request->latitude;
        $doctor->item_lng = $request->longitude;
        $doctor->item_price = $request->item_price;
        $doctor->item_website = $request->item_website;
        // $doctor->categories  = implode(",",$request->categories);
        $doctor->item_social_facebook = $request->item_social_facebook;
        $doctor->item_social_twitter = $request->item_social_twitter;
        $doctor->item_social_linkedin = $request->item_social_linkedin;
        $doctor->item_social_whatsapp = $request->item_social_whatsapp;
        $doctor->item_social_instagram = $request->item_social_instagram;
        //end new fields

        if(isset($request->password)){

            $password = trim($request->password);
            $password = Hash::make($password);
            $doctor->password = $password;

        }
        $doctor->save();
        //Data save to dises basis type
        $petType     = PetType::orderBy('name')->get();
        foreach ($petType as $ptype){
            $ptid = $ptype->id;
            if(isset($request->pet_type_id[$ptid]) && isset($request->disease_id[$ptid]) ){
                if($request->pet_type_id[$ptid] == $ptid ){
                    $desid ='';
                    foreach($request->disease_id[$ptid] as $key=>$val ){
                        if ($key === array_key_last($request->disease_id[$ptid])) {
                            $desid.=$val;
                        }else{
                            $desid.=$val.",";
                        }
                    }
                    $pet_diseases_on_type_basiscount  = DB::table('pet_diseases_on_type_basis')->where('doc_id', $doctor->id)->where('pet_type_id',$ptid)->count();
                    if($pet_diseases_on_type_basiscount > 0){
                        //not Selected the dises drop the exsist value
                        if($desid==''){
                            //dd(1);
                            DB::table('pet_diseases_on_type_basis')->where('pet_type_id', '=',$ptid)->where('doc_id', $doctor->id)->delete();
                        }else{
                            DB::table('pet_diseases_on_type_basis')->where('doc_id', $doctor->id)->where('pet_type_id',$ptid)->update([
                                'doc_id' => $doctor->id,
                                'pet_type_id' => $ptid,
                                'disease_id' => $desid
                            ]);
                        }
                    }else{
                        DB::table('pet_diseases_on_type_basis')->insert([
                            'doc_id' => $doctor->id,
                            'pet_type_id' => $ptid,
                            'disease_id' => $desid
                        ]);
                    }
                }
            }else{
               DB::table('pet_diseases_on_type_basis')->where('pet_type_id', '=',$ptid)->where('doc_id', $doctor->id)->delete();
            }
        }
    }

    public function detail($id)
    {
        $doctor            = Doctor::findOrFail($id);
        $pageTitle         = 'Doctor Detail - ' . $doctor->name;
        $departments       = Department::latest()->get();
        $petType           = PetType::orderBy('name')->get();
        $locations         = Location::latest()->get();
        $totalOnlineEarn   = Deposit::where('doctor_id', $doctor->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $totalCashEarn     = $doctor->balance - $totalOnlineEarn;
        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->where('try', 1)->count();

        $completeAppointments = Appointment::where('doctor_id', $doctor->id)->where('try', 1)->where('is_complete', Status::YES)->count();
        $trashedAppointments  = Appointment::where('doctor_id', $doctor->id)->where('is_delete', Status::YES)->count();

        $countries   = Country::all();
        $states      = States::all();
        $cities      = Cities::all();
        $categories  = Category::all();
        $PetDisease  = PetDisease::all();

        return view('admin.doctor.details', compact('categories','pageTitle','countries','states','cities' ,'doctor', 'departments', 'locations', 'totalOnlineEarn', 'totalCashEarn', 'completeAppointments', 'trashedAppointments', 'totalAppointments','petType','PetDisease'));
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

    public function vetReviews($id)
    {
        $doctor    = Doctor::findOrFail($id);
        $vetReviews = VetReviews::with('user')->where('doctor_id', $id)->orderBy('id', 'desc')->get();
        $logs      = NotificationLog::where('doctor_id', $id)->with('doctor')->orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle = $doctor->name.' Reviews';
        return view('admin.doctor.reviews', compact('pageTitle', 'logs', 'doctor','vetReviews'));
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
