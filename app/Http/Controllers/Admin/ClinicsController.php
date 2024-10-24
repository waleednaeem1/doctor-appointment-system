<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Clinics;
use App\Models\Location;
use App\Models\Country;
use App\Models\Cities;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\States;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ClinicsController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Clinics';
        $clinics = $this->commonQuery()->paginate(getPaginate());
        return view('admin.clinics.index', compact('pageTitle', 'clinics'));
    }

    public function active($status)
    {
        $pageTitle = 'Active Doctors';
        $assistants = $this->commonQuery()->where('status', Status::ACTIVE)->paginate(getPaginate());
        return view('admin.assistant.index', compact('pageTitle', 'assistants'));
    }
    public function inactive($status)
    {
        $pageTitle = 'Inactive Doctors';
        $assistants = $this->commonQuery()->where('status', Status::INACTIVE)->paginate(getPaginate());
        return view('admin.assistant.index', compact('pageTitle', 'assistants'));
    }

    protected function commonQuery(){
        return Clinics::orderBy('id', 'DESC')->searchable(['name', 'phone', 'clinic_owner'])->filter(['phone']);
    }

    public function status($id)
    {
        return Assistant::changeStatus($id);
    }

    public function form()
    {
        $pageTitle = 'Add New Clinics';
        $departments = Department::orderBy('name')->get();
        $locations   = Location::orderBy('name')->get();
        $categories  = Category::all();
        $countries   = Country::all();
        $states      = States::all();
        $doctors     = Doctor::active()->orderBy('name')->get();
        $staffs       = Staff::active()->orderBy('name')->get();
        return view('admin.clinics.form', compact('pageTitle','states','countries', 'doctors','departments','locations','categories','staffs'));
    }

    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        if ($id) {
            $clinics    = Clinics::findOrFail($id);
            $notification = 'Clinics updated successfully';
        } else {
            $clinics    = new Clinics();
            $notification = 'Clinics added successfully';
        }
        
        $this->clinicSave($clinics, $request);

        // if (!$id) {
        //     $general = gs();
        //     notify($assistant, 'PEOPLE_CREDENTIAL', [
        //         'site_name' => $general->site_name,
        //         'name'      => $assistant->name,
        //         'username'  => $assistant->username,
        //         'password'  => decrypt($assistant->password),
        //         'guard'     => route('login'),
        //     ]);
        // }
        $notify[] = ['success', $notification];
        return to_route('admin.clinics.detail', $clinics->id)->withNotify($notify);
    }


    protected function validation($request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'logo'     => ["$imageValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'name'     => 'required|string|max:40|min:6|unique:clinics,name,' . $id,
            'phone'                 => 'required|string|max:25',
            'address'                => 'required',
            // 'city'                  =>  'required',
            'doctor_id'             =>  'required',
            // 'staff_id'             =>  'required',
            // 'country'               =>  'required',
            // 'state'                 =>  'required',
            'timings'               =>  'required',
            'department'            =>  'required',
            // 'categories'            =>  'required',
            'week_days'             =>  'required',
            'clinic_owner'          =>  'required',
            'clinic_owner_phone'    =>  'required'
        ]);
    }

    protected function clinicSave($clinics, $request)
    {
        $doctors = Doctor::findOrFail($request->doctor_id);
        // $staffs = Staff::findOrFail($request->staff_id);
        
        if ($request->hasFile('logo')) {
            try {
                $old = $clinics->logo;
                $clinics->logo = fileUploader($request->logo, getFilePath('clinic'), getFileSize('clinic'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $general = gs();
        
        $clinics->name                           = $request->name;
        $clinics->phone                          = $request->phone;
        $clinics->address                         = $request->address;
        $clinics->city                           = $request->city;
        $clinics->country                        = $request->country;
        $clinics->state                          = $request->state;
        $clinics->postal_code                    = $request->postal_code;
        $clinics->latitude                       = $request->latitude;
        $clinics->longitude                      = $request->longitude;
        $clinics->timings                        = $request->timings;
        $clinics->department                     = implode(",",$request->department);
        $clinics->doctor_id                      = implode(",",$request->doctor_id);
        // $clinics->categories                  = implode(",",$request->categories);
        $clinics->week_days                      = $request->week_days;
        $clinics->clinic_owner                   = $request->clinic_owner;
        $clinics->clinic_owner_phone             = $request->clinic_owner_phone;
        $clinics->meta_title                     = $request->meta_title;
        $clinics->meta_keywords                  = $request->meta_keywords;
        $clinics->meta_description               = $request->meta_description;
        $clinics->description                   = $request->description;
        $clinics->save();
        if ($doctors) {
            $clinics->doctors()->sync($doctors->pluck('id'));
        }
        // if ($staffs) {
        //     $clinics->staffs()->sync($staffs->pluck('id'));
        // }
    }


    public function detail($id)
    {  
        $clinics    = Clinics::findOrFail($id);
        $pageTitle    = 'Clinic Detail - ' . $clinics->name;
        $doctors      = Doctor::orderBy('name')->get();
        $staffs       = Staff::orderBy('name')->get();
        $totalDoctors = $clinics->doctors->count();
        $departments = Department::orderBy('name')->get();
        $locations   = Location::orderBy('name')->get();
        $categories  = Category::all();
        $countries   = Country::all();
        $states      = States::all();    
        // $basicQuery          = Appointment::where('try', Status::YES)->where('is_delete', Status::NO)->where('added_assistant_id', $id);
        // $totalCount          = clone $basicQuery;
        // $completeCount       = clone $basicQuery;
        // $newCount            = clone $basicQuery;
        // $totalAppointment    = $totalCount->count();
        // $completeAppointment = $completeCount->where('is_complete', Status::APPOINTMENT_COMPLETE)->count();
        // $newAppointment      = $newCount->where('is_complete', Status::APPOINTMENT_INCOMPLETE)->count();

        return view('admin.clinics.details', compact('pageTitle', 'clinics', 'doctors', 'totalDoctors','states','departments','locations','categories','countries','staffs'));
    }


    public function login($id)
    {
        $assistant = Assistant::findOrFail($id);
        Auth::guard('assistant')->login($assistant);
        return redirect()->route('assistant.dashboard');
    }

    public function notificationLog($id)
    {
        $assistant = Assistant::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $assistant->username;
        $logs      = NotificationLog::where('assistant_id', $id)->with('assistant')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.assistant.notification_history', compact('pageTitle', 'logs', 'assistant'));
    }

    public function showNotificationSingleForm($id)
    {
        $assistant = Assistant::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.admin.detail', $assistant->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $assistant->username;
        return view('admin.assistant.notification_single', compact('pageTitle', 'assistant'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $assistant = Assistant::findOrFail($id);
        notify($assistant, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return to_route('admin.assistant.notification.log', $assistant->id)->withNotify($notify);
    }


    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $assistants = Assistant::active()->count();
        $pageTitle = 'Notification to Verified Assistants';
        return view('admin.assistant.notification_all', compact('pageTitle', 'assistants'));
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

        $assistant = Assistant::active()->skip($request->skip)->first();

        if (!$assistant) {
            return response()->json([
                'error' => 'Assistant not found',
                'total_sent' => 0,
            ]);
        }

        notify($assistant, 'DEFAULT', [
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
        $logs = AssistantLogin::orderByDesc('id')->searchable(['assistant:username,name'])->with('assistant');
        if ($id) {
            $assistant = Assistant::find($id);
            $pageTitle = $assistant->name. ' '.'Login History';
            $loginLogs = $logs->where('assistant_id', $id)->paginate(getPaginate());
        } else {
            $pageTitle = 'Assistant Login History';
            $loginLogs = $logs->paginate(getPaginate());
        }
        return view('admin.assistant.logins', compact('pageTitle', 'loginLogs'));
    }

    public function getStateByCountry(Request $request)
    {
        
        $states = States::where('country_id', $request->country)->orderBy('name','ASC')->get();
        return response()->json($states, 200);
    }

    public function getCityByStates(Request $request)
    {
        
        $states = Cities::where('state_id', $request->state)->orderBy('city_name','ASC')->get();
        return response()->json($states, 200);
    }

    // public function index()
    // {   
    //     $pageTitle   = 'All Clinics';
    //     $clinics     = Clinics::searchable(['name'])->orderBy('name')->paginate(getPaginate());
    //     $departments = Department::orderBy('name')->get();
    //     $locations   = Location::orderBy('name')->get();
    //     $categoires  = Category::all();
    //     $countries   = Country::all();
    //     $cities      = Cities::all();
    //     $states      = States::all();
    //     $doctors     = Doctor::all();
        
    //     return view('admin.clinics.index', compact('pageTitle','doctors','clinics','departments','locations','countries','cities','states','categoires'));
    // }

    // public function store(Request $request, $id = 0)
    // {
       
    //     $this->validation($request, $id);

    //     if ($id) {
    //         $clinic         = Clinics::findOrFail($id);
    //         $notification       = 'Clinic updated successfully';
    //     } else {
    //         $clinic         = new Clinics();
    //         $notification       = 'Clinic added successfully';
    //     }

    //     $this->clinicSave($clinic, $request);

    //         $notify[] = ['success', $notification];
    //     return to_route('admin.clinics.index', $clinic->id)->withNotify($notify);
    // }

    // protected function validation($request, $id = 0)
    // {
    //     $imageValidation = $id ? 'nullable' : 'required';
    //     $request->validate([
    //         'logo'      => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
    //         'name'                  => 'required|string|max:40|min:6|unique:clinics,name,' . $id,
    //         'phone'                 => 'required|string|max:25',
    //         'adress'                => 'required',
    //         'city'                  =>  'required',
    //         'doctor_id'             =>  'required',
    //         'country'               =>  'required',
    //         'state'                 =>  'required',
    //         'timings'               =>  'required',
    //         'department'            =>  'required',
    //         'categories'            =>  'required',
    //         'week_days'             =>  'required',
    //         'clinic_owner'          =>  'required',
    //         'clinic_owner_phone'    =>  'required',
    //         'meta_title'            =>  'required',
    //         'meta_keywords'         =>  'required',
    //         'meta_description'      =>  'required',

    //     ]);
    // }


    // protected function clinicSave($clinic, $request)
    // {
    //     if ($request->hasFile('logo')) {
    //         try {
    //             $old = $clinic->logo;
    //             $clinic->logo = fileUploader($request->logo, getFilePath('clinic'), getFileSize('clinic'), $old);
    //         } catch (\Exception $exp) {
    //             $notify[] = ['error', 'Couldn\'t upload your image'];
    //             return back()->withNotify($notify);
    //         }
    //     }

    //     $clinic->name                           = $request->name;
    //     $clinic->phone                          = $request->phone;
    //     $clinic->adress                         = $request->adress;
    //     $clinic->city                           = $request->city;
    //     $clinic->country                        = $request->country;
    //     $clinic->state                          = $request->state;
    //     $clinic->timings                        = $request->timings;
    //     $clinic->department                     = $request->department;
    //     $clinic->categories                     = $request->categories;
    //     $clinic->doctor_id                      = $request->doctor_id;
    //     $clinic->week_days                      = $request->week_days;
    //     $clinic->clinic_owner                   = $request->clinic_owner;
    //     $clinic->clinic_owner_phone             = $request->clinic_owner_phone;
    //     $clinic->meta_title                     = $request->meta_title;
    //     $clinic->meta_keywords                  = $request->meta_keywords;
    //     $clinic->meta_description               = $request->meta_description;
    //     $clinic->save();
    // }

    // public function location()
    // {
    //     $pageTitle = 'All Locations';
    //     $locations   = Location::searchable(['name'])->orderBy('name')->paginate(getPaginate());
    //     return view('admin.location.index', compact('pageTitle', 'locations'));
    // }

    // public function locationStore(Request $request, $id = 0)
    // {
    //     $request->validate([
    //         'name'       => 'required|unique:locations|max:40'
    //     ]);

    //     if ($id) {
    //         $location           = Location::findOrFail($id);
    //         $notification       = 'Location updated successfully';
    //     } else {
    //         $location           = new Location();
    //         $notification       = 'Location added successfully';
    //     }

    //     $location->name    = $request->name;
    //     $location->save();
    //     $notify[] = ['success', $notification];
    //     return back()->withNotify($notify);
    // }

        

}
