<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Location;
use App\Models\States;
use App\Models\User;
use App\Models\UserPets;
use App\Constants\Status;
use App\Models\Appointment;
use App\Models\Favorite;
use App\Models\AssistantDoctorTrack;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class DoctorAppointmentController extends Controller
{
    public $userType;
    public function doctors(Request $request)
    {
        $data['pageTitle']   = 'Our Doctors';
        $doctors     = Doctor::active();

        $doctors = $doctors->orderBy('id', 'DESC')->with('favorite','reviews','educationDetails','experienceDetails','socialIcons')->get();
        if($doctors->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Doctor not found', 'data' => []], 200);
        }
        $data['doctors'] = new Collection();
        $userId = $request->user_id;
        $todayDate = Carbon::today()->format('Y-m-d');
        $tomorrowDate = Carbon::tomorrow()->format('Y-m-d');
        $dates = [$todayDate, $tomorrowDate];
        foreach($doctors as $doctor){
            $stateDetail = States::where('id', $doctor->state_id)->first(array('id','name'));
            $countryDetail = Country::where('id', $doctor->country_id)->first(array('id','name'));
            $cityDetail = Cities::where('id', $doctor->city_id)->first(array('id','city_name'));
            $departmentDetail = Department::where('id', $doctor->department_id)->first(array('id','name'));

            $doctor->state = $stateDetail->name ?? null;
            $doctor->country = $countryDetail->name ?? null;
            $doctor->city = $cityDetail->city_name ?? null;
            $doctor->department = $departmentDetail->name ?? null;

            $check = Favorite::where('doctor_id', $doctor->id)
                    ->where('user_id', $userId)
                    ->exists();

                $isAvailableToday = false;
                $isAvailableTomorrow = false;

                foreach ($dates as $date) {
                    if ($doctor->weekday && in_array($date, json_decode($doctor->weekday))) {
                        $collection = Appointment::hasDoctor()
                            ->where('doctor_id', $doctor->id)
                            ->where('try', Status::YES)
                            ->where('is_delete', Status::NO)
                            ->whereDate('booking_date', $date)
                            ->get();

                        $isAvailable = true;

                        foreach ($collection as $value) {
                            if (in_array($value->time_serial, $doctor->serial_or_slot)) {
                                $isAvailable = false;
                                break;
                            }
                        }

                        if ($date === $todayDate) {
                            $isAvailableToday = $isAvailable;
                        } elseif ($date === $tomorrowDate) {
                            $isAvailableTomorrow = $isAvailable;
                        }
                    }
                }

                $doctorData = $doctor->toArray();
                $doctorData['is_favourite'] = $check ? true : false;
                $doctorData['todayAvailable'] = $isAvailableToday;
                $doctorData['tomorrowAvailable'] = $isAvailableTomorrow;

            $data['doctors']->push($doctorData);
        }
        $lastPage = intval(ceil($data['doctors']->count()/15));
        $perPage = 15;
        $page = request('page', 1);
        $data['currentPage'] = (int)$page;
        $data['lastPage'] = $lastPage;
        $paginatedData = $data['doctors']->forPage($page, $perPage);
        $data['doctors'] = $paginatedData->values();
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function addToFavorite($doctorId, $user)
    {
        $Favorite = Favorite::where(['doctor_id' => $doctorId, 'user_id' => $user])->first();
        if($Favorite){
            $Favorite->delete();
            return response()->json(['success' => true, 'msg' => 'Doctor remove from favorite', 'Favorite' => $Favorite]);
        }
        else{
            $Favorite = new Favorite();
            $Favorite->doctor_id = $doctorId;
            $Favorite->user_id = $user;
            $Favorite->save();
            return response()->json(['success' => true, 'msg' => 'Doctor add to favorite', 'Favorite' => $Favorite]);
        }

    }
    public function favoriteDoctorList($userId)
    {
        $doctors = Doctor::active();
        $doctors = $doctors->orderBy('id', 'DESC')
                           ->with('department', 'location', 'favorite')
                           ->whereHas('favorite', function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            })
                           ->get();
        return response()->json(['success' => true, 'doctors' => $doctors]);

    }

    public function departmentDoctors($departmentId){
        $doctors     = Doctor::active();
        $data['doctors'] = $doctors->where('department_id', $departmentId);
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function search(Request $request)
    {
        $pageTitle   = 'Our Doctors';
        $doctors     = Doctor::active();
        if ($request->has('location')) {
            $doctors = $doctors->where('location_id', $request->location);
        }
        if ($request->has('department')) {
            $doctors = $doctors->where('department_id', $request->department);
        }
        if ($request->has('doctor')) {
            $doctors = $doctors->where('id', $request->doctor);
        }
        $doctors = $doctors->orderBy('id', 'DESC')->with('department', 'location')->get();
        if($doctors->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Doctor not found', 'data' => []], 200);
        }
        $data=[
            'pageTitle' => $pageTitle,
            'doctors' => $doctors,
        ];
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function statesByDoctor($filter)
    {

        $States = States::select('id','name');
        if (isset($filter)) {
            $keywords = $filter;
            $singular = Str::singular($keywords);
            $plural   = Str::plural($keywords);
            $search   = [];
            array_push($search, $keywords);
            if ($singular != $keywords)
                array_push($search, $singular);
            if ($plural != $keywords)
                array_push($search, $plural);

            $States->where(function ($States) use ($search) {
                foreach ($search as $keyword) {
                    $States->orWhere('name', 'like', '%' . $keyword . '%');
                }
            });
            $States = $States->pluck('id')->toArray();
            $data['doctors'] = Doctor::active()->whereIn('state_id', $States)->orderBy('id', 'DESC')->with('department', 'state')->get();
            return response()->json(['success' => true, 'data' => $data], 200);
        }
    }

    public function departments($department)
    {
        $pageTitle   = 'Department wise Doctors';
        $doctors     = Doctor::active()->where('department_id', $department)->orderBy('id', 'DESC')->with('department', 'location')->get();
        if($doctors->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Doctor not found', 'data' => []], 200);
        }
        $data=[
            'pageTitle' => $pageTitle,
            'doctors' => $doctors,
        ];
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function booking($id = 0)
    {
        $doctor = Doctor::findOrFail($id);
        $userPets   = UserPets::orderBy('id', 'ASC')->get();

        if (!$doctor->status) {
            return response()->json(['success' => false, 'error' => 'This doctor is inactive!'], 201);
        }

        $pageTitle = $doctor->name . ' - Booking';
        $availableDate = [];
        $date = Carbon::now();
        for ($i = 0; $i < $doctor->serial_day; $i++) {
            array_push($availableDate, date('Y-m-d', strtotime($date)));
            $date->addDays(1);
        }
        $data=[
            'pageTitle' => $pageTitle,
            'availableDate' => $availableDate,
            'doctor' => $doctor,
            'userPets' => $userPets,
        ];
        return response()->json(['success' => true, 'data' => $data], 201);
    }

    public function availability(Request $request)
    {
        $doctor = Doctor::active()->find($request->doctor_id);
        if (!$doctor) {
            return response()->json(['Success' => false, 'msg' => 'The doctor isn\'t available for the appointment', 'data' => []], 200);
        }
        if (!$doctor->serial_or_slot) {
            return response()->json(['Success' => false, 'msg' => 'No available schedule for this doctor', 'data' => []], 200);
        }
        $bookTimeSerial = Appointment::where('doctor_id', $doctor->id)->where('booking_date', $request->booking_date)->where('try', Status::YES)->where('is_delete', Status::NO)->pluck('time_serial')->toArray();

        $todayDate = Carbon::today()->format('Y-m-d');
        $currentTime = Carbon::now();
        if( $todayDate != $request->booking_date )
            $currentTime = Carbon::createFromFormat('h:i:a', '12:00:am');

        $timeSlots = array();
        if ($bookTimeSerial) {
            foreach ($doctor->serial_or_slot as $value) {
                $slotTime = Carbon::createFromFormat('h:i:a', $value);
                if($currentTime < $slotTime){
                    if (!in_array($value, $bookTimeSerial)) {
                        $time = [
                            'time' => $value,
                            'availability' => 'available',
                        ];
                    } else {
                        $time = [
                            'time' => $value,
                            'availability' => 'booked',
                        ];
                    }
                    $timeSlots[] = $time;
                }
            }
        } else {
            foreach ($doctor->serial_or_slot as $key => $value) {
                $slotTime = Carbon::createFromFormat('h:i:a', $value);
                if($currentTime < $slotTime){
                    $time = [
                        'time' => $value,
                        'availability' => 'available',
                    ];
                    $timeSlots[] = $time;
                }
            }
        }

        return response()->json(['success' => true, 'serial_or_slot' => $timeSlots], 201);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name'           => 'required|max:40',
            'booking_date'   => 'required|date|after_or_equal:today',
            'time_serial'    => 'required',
            'user_pet_id'    => 'required',
            'email'          => 'required|email',
            'mobile'         => 'required|max:40',
            'payment_system' => 'required|in:1,2',
        ],
        [
            'time_serial.required' => 'You did not select any time or Serial',
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }

        $doctor = Doctor::active()->find($id);

        if (!$doctor) {
            return response()->json(['success' => false, 'error' => 'The doctor isn\'t available for the appointment'], 201);
        }

        if (!$doctor->serial_or_slot) {
            return response()->json(['success' => false, 'error' => 'No available schedule for this doctor'], 201);
        }

        $timeSerialCheck = $doctor->whereJsonContains('serial_or_slot', $request->time_serial)->exists();

        if (!$timeSerialCheck) {
            return response()->json(['success' => false, 'error' => 'This time or serial is already booked. Please try another time or serial'], 201);
        }

        $existed = Appointment::where('doctor_id', $doctor->id)->where('booking_date', $request->booking_date)->where('time_serial', $request->time_serial)->where('try', Status::YES)->where('is_delete', Status::NO)->exists();

        if ($existed) {
            return response()->json(['success' => false, 'error' => 'This time or serial is already booked. Please try another time or serial'], 201);
        }

        if ($this->userType == 'assistant') {
            $doctorCheck = AssistantDoctorTrack::where('assistant_id', auth()->guard('assistant')->id())->where('doctor_id', $doctor->id)->first();

            if (!$doctorCheck) {
                return response()->json(['success' => false, 'error' => 'You don\'t have permission to operate this action'], 201);
            }
        }

        /**
         *Site: Gateway payment is via online. payment_system is cash==2 and  gateways==1;
        **/

        $gateways = ($request->payment_system == 1) ? Status::YES : Status::NO;
        $general  = gs();
        $mobile   =  $general->country_code . $request->mobile;

        //save
        $appointment               = new Appointment();
        $appointment->booking_date = Carbon::parse($request->booking_date)->format('Y-m-d');
        $appointment->time_serial  = $request->time_serial;
        $appointment->name         = $request->name;
        $appointment->email        = $request->email;
        $appointment->mobile       = $mobile;
        $appointment->doctor_id    = $doctor->id;
        $appointment->user_pets_id = $request->user_pet_id;
        $appointment->disease      = $request->disease;
        $appointment->try          = $gateways ? Status::NO : Status::YES;
        $appointment->trx          = $gateways ?  getTrx() : NULL;

        if ($this->userType == 'admin') {
            $appointment->added_admin_id = 1;
        } elseif ($this->userType == 'doctor') {
            $appointment->added_doctor_id = auth()->guard('doctor')->id();
        } elseif ($this->userType == 'staff') {
            $appointment->added_staff_id = auth()->guard('staff')->id();
        } elseif ($this->userType == 'assistant') {
            $appointment->added_assistant_id = auth()->guard('assistant')->id();
        } else {
            $appointment->site = Status::YES;
        }

        if ($gateways) {
            $appointment->try  = Status::NO;
        } else {
            $appointment->try  = Status::YES;
        }

        $appointment->save();
        if($request->payment_system == 1 && $gateways){
            if ($gateways) {
                $appointment->payment_status = Status::APPOINTMENT_PENDING_PAYMENT;
                $appointment->save();

                $pageTitle = "Appointment Payment Method";
                $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
                    $gate->where('status', Status::ENABLE);
                })->with('method')->orderby('method_code')->get();

                $validator = Validator::make($request->all(),[
                    'currency' => 'required',
                    'number' => 'required',
                    'exp_month' => 'required',
                    'cvc' => 'required',
                    'exp_year' => 'required',
                ]);

                if ($validator->fails()){
                    return response()->json(['success' => false, 'error' => $validator->messages()], 201);
                }

                $appointment = Appointment::where('trx', $appointment->trx)->first();
                if (!$appointment) {
                    return response()->json(['success' => false, 'error' => 'Invalid appointment!'], 201);
                }

                Stripe::setApiKey(config('app.STRIPE_SECRET'));
                $token = Stripe::tokens()->create([
                    'card' => [
                        'number'    => $request->number,
                        'exp_month' => $request->exp_month,
                        'cvc'       => $request->cvc,
                        'exp_year'  => $request->exp_year
                    ],
                ]);
                $charge = Stripe::charges()->create([
                    'source' => $token['id'],
                    'currency' => 'USD',
                    'amount' => round($doctor->fees,2),
                    'metadata' => [
                        'name' => 'ishtiaq',
                        'email' => 'ishtiaq.gmit@gmail.com',
                        'phone' => '123456789'
                    ]
                ]);
                if($charge['status'] == 'succeeded')
                {
                    $charge['amount']                       =   number_format($charge['amount'],2);
                    $charge['application_fee_amount']       =   number_format($charge['application_fee_amount'],2);
                    $charge['amount_captured']              =   number_format($charge['amount_captured'],2);

                    $data = new Deposit();
                    $data->appointment_id  = $appointment->id;
                    $data->doctor_id       = $doctor->id;
                    $data->method_code     = 2;
                    $data->method_currency = strtoupper($charge['currency']);
                    $data->amount          = $charge['amount'];
                    $data->charge          = $charge['application_fee_amount'] ? $charge['application_fee_amount'] : 0 ;
                    $data->rate            = $charge['application_fee'] ? $charge['application_fee'] : 0 ;
                    $data->final_amo       = $charge['amount_captured'] ? $charge['amount_captured'] : 0;
                    $data->btc_amo         = 0;
                    $data->btc_wallet      = "";
                    $data->trx             = $appointment->trx;
                    $data->save();
                    session()->put('Track', $data->trx);
                    $appointment->update(['try' => Status::YES]);
                    return response()->json(['success' => true, 'msg' => 'New Appointment make and Transaction Completed'], 200);
                }else{
                    return response()->json(['success' => false, 'error' => 'Transaction Error'], 201);
                }
                //End Stripe Payment

                $gate = GatewayCurrency::whereHas('method', function ($gate) {
                    $gate->where('status', Status::ENABLE);
                })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
                if (!$gate) {
                    return response()->json(['success' => false, 'error' => 'Invalid gateway'], 201);
                }

                if ($gate->min_amount > $doctor->fees || $gate->max_amount < $doctor->fees) {
                    return response()->json(['success' => false, 'error' => 'Please follow deposit limit'], 201);
                }

                $charge    = $gate->fixed_charge + ($doctor->fees * $gate->percent_charge / 100);
                $payable   = $doctor->fees + $charge;
                $finalAmount = $payable * $gate->rate;

                $data = new Deposit();
                $data->appointment_id  = $appointment->id;
                $data->doctor_id       = $doctor->id;
                $data->method_code     = $gate->method_code;
                $data->method_currency = strtoupper($gate->currency);
                $data->amount          = $doctor->fees;
                $data->charge          = $charge;
                $data->rate            = $gate->rate;
                $data->final_amo       = $finalAmount;
                $data->btc_amo         = 0;
                $data->btc_wallet      = "";
                $data->trx             = $appointment->trx;
                $data->save();
                session()->put('Track', $data->trx);
                return response()->json(['success' => true, 'msg' => 'Deposit Confirm'], 200);
            }
        }else{
            notify($this->notifyUser($appointment), 'APPOINTMENT_CONFIRMATION', [
                'booking_date' => $appointment->booking_date,
                'time_serial'  => $appointment->time_serial,
                'doctor_name'  => $doctor->name,
                'doctor_fees'  => '' . $doctor->fees . ' ' . $general->cur_text . '',
            ]);
            return response()->json(['success' => true, 'msg' => 'New Appointment make successfully'], 201);

        }
    }

    protected  function notifyUser($appointment)
    {
        $user = [
            'name'     => $appointment->name,
            'username' => $appointment->email,
            'fullname' => $appointment->name,
            'email'    => $appointment->email,
            'mobile'   => $appointment->mobile,
        ];
        return $user;
    }

    public function appointmentList($userId){
        $user = User::find($userId);
        if($user){
            $appointments = Appointment::whereHas('pet', function($q) use ($userId)
            {
                $q->whereHas('user', function($q) use ($userId)
                {
                    $q->whereIn('id', array($userId));
                });
            })->with('doctor','pet','doctor.educationDetails','doctor.experienceDetails','doctor.socialIcons','pet.attachments')->get();
            $pastAppointmentList = [];
            $upcomingAppointmentList = [];
        foreach($appointments as $appointment){
            $userPetsdata = [];

            $petattachments = [];
            $previousRecord = [];
            // $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $userPet->created_at)->format('d-m-Y, h:i a');
            foreach($appointment->pet->attachments as $petAttachment){
                if($petAttachment->attachment_type == 'previous_record'){
                    $extension = pathinfo($petAttachment->attachment, PATHINFO_EXTENSION);
                    if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])){
                        $previousRecord[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'image',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }elseif(in_array($extension, ['mp4', 'mov', 'wmv', 'avi', 'mkv', 'flv', 'webm'])){
                        $previousRecord[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'video',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }else{
                        $previousRecord[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'file',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }
                }else{
                    $extension = pathinfo($petAttachment->attachment, PATHINFO_EXTENSION);
                    if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])){
                        $petattachments[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'image',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }elseif(in_array($extension, ['mp4', 'mov', 'wmv', 'avi', 'mkv', 'flv', 'webm'])){
                        $petattachments[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'video',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }else{
                        $petattachments[] = (object)[
                            'id' => $petAttachment->id,
                            'pet_id' => $petAttachment->pet_id,
                            'user_id' => $petAttachment->user_id,
                            'attachment' => $petAttachment->attachment,
                            'attachment_type' => $petAttachment->attachment_type,
                            'type' => 'file',
                            'created_at' => $petAttachment->created_at,
                            'updated_at' => $petAttachment->updated_at,
                        ];
                    }
                }
            }
            $userPetsdata = (object)[
                'id' => $appointment->pet->id,
                'user_id' => $appointment->pet->user_id,
                'name' => $appointment->pet->name,
                'age' => $appointment->pet->age,
                'age_in' => $appointment->pet->age_in,
                'breed' => $appointment->pet->breed,
                'gender' => $appointment->pet->gender,
                'weight' => $appointment->pet->weight,
                'unit' => $appointment->pet->unit,
                'species' => $appointment->pet->pettype ? $appointment->pet->pettype->name : null,
                'slug' => $appointment->pet->slug,
                'short_description' => $appointment->pet->short_description,
                'status' => $appointment->pet->status,
                'pet_type_id' => $appointment->pet->pet_type_id,
                'meta_title' => $appointment->pet->meta_title,
                'meta_keywords' => $appointment->pet->meta_keywords,
                'meta_description' => $appointment->pet->meta_description,
                'created_at' => $appointment->pet->created_at,
                'updated_at' => $appointment->pet->updated_at,
                'attachments' => $petattachments,
                'previous_record' => $previousRecord,
            ];




            $date = Carbon::createFromFormat('Y-m-d', $appointment->booking_date);
            if($date < Carbon::today()){
                $bookingdate = $date->format('d M, Y');
                $pastAppointmentList[] = (object)[
                    'id' => $appointment->id,
                    'doctor_id' => $appointment->doctor_id,
                    'user_pets_id' => $appointment->user_pets_id,
                    'added_doctor_id' => $appointment->added_doctor_id,
                    'added_assistant_id' => $appointment->added_assistant_id,
                    'added_staff_id' => $appointment->added_staff_id,
                    'added_admin_id' => $appointment->added_admin_id,
                    'site' => $appointment->site,
                    'name' => $appointment->name,
                    'email' => $appointment->email,
                    'mobile' => $appointment->mobile,
                    'age' => $appointment->age,
                    'disease' => $appointment->disease,
                    'booking_date' => $bookingdate,
                    'time_serial' => $appointment->time_serial,
                    'payment_status' => $appointment->payment_status,
                    'trx' => $appointment->trx,
                    'try' => $appointment->try,
                    'is_complete' => $appointment->is_complete,
                    'is_delete' => $appointment->is_delete,
                    'd_doctor' => $appointment->d_doctor,
                    'delete_by_assistant' => $appointment->delete_by_assistant,
                    'delete_by_staff' => $appointment->delete_by_staff,
                    'delete_by_admin' => $appointment->delete_by_admin,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'doctor' => $appointment->doctor,
                    'pet' => $userPetsdata,
                ];
            }else{
                $bookingdate = $date->format('d M, Y');
                $upcomingAppointmentList[] = (object)[
                    'id' => $appointment->id,
                    'doctor_id' => $appointment->doctor_id,
                    'user_pets_id' => $appointment->user_pets_id,
                    'added_doctor_id' => $appointment->added_doctor_id,
                    'added_assistant_id' => $appointment->added_assistant_id,
                    'added_staff_id' => $appointment->added_staff_id,
                    'added_admin_id' => $appointment->added_admin_id,
                    'site' => $appointment->site,
                    'name' => $appointment->name,
                    'email' => $appointment->email,
                    'mobile' => $appointment->mobile,
                    'age' => $appointment->age,
                    'disease' => $appointment->disease,
                    'booking_date' => $bookingdate,
                    'time_serial' => $appointment->time_serial,
                    'payment_status' => $appointment->payment_status,
                    'trx' => $appointment->trx,
                    'try' => $appointment->try,
                    'is_complete' => $appointment->is_complete,
                    'is_delete' => $appointment->is_delete,
                    'd_doctor' => $appointment->d_doctor,
                    'delete_by_assistant' => $appointment->delete_by_assistant,
                    'delete_by_staff' => $appointment->delete_by_staff,
                    'delete_by_admin' => $appointment->delete_by_admin,
                    'created_at' => $appointment->created_at,
                    'updated_at' => $appointment->updated_at,
                    'doctor' => $appointment->doctor,
                    'pet' => $userPetsdata,
                ];
            }
        }

            return response()->json(['success' => true, 'upcomingAppointmentList' => $upcomingAppointmentList, 'pastAppointmentList' => $pastAppointmentList], 200);
        }else{
            return response()->json(['success' => false, 'msg' => 'User not found.'], 200);
        }
    }

    public function featureDoctors(Request $request){
        $userId = $request->user_id;
        // $alldoctors = Doctor::all();
        $alldoctors = Doctor::active()->get();
        $data['featuredDoctors'] = new Collection();

        foreach ($alldoctors as $doctor) {
            $check = Favorite::where('doctor_id', $doctor->id)
                        ->where('user_id', $userId)
                        ->exists();
                        $todayDate = Carbon::today()->format('Y-m-d');
            $tommorowDate = Carbon::tomorrow()->format('Y-m-d');
            $dates = [$todayDate, $tommorowDate];
            foreach ($dates as $date) {
                $collection = Appointment::hasDoctor()->where('doctor_id', $doctor->id)->where('try', Status::YES)->where('is_delete', Status::NO)->whereDate('booking_date', $date)->get();

                $availability = $date === $todayDate ? 'todayAvailable' : 'tommorowAvailable';
                $doctor->$availability = true;

                foreach ($collection as $value) {
                    if (in_array($value->time_serial, $doctor->serial_or_slot)) {
                        $doctor->$availability = false;
                        break;
                    }
                }
            }
            $data['featuredDoctors']->push([
                'id' => $doctor->id,
                'name' => $doctor->name,
                'username' => $doctor->username,
                'email' => $doctor->email,
                'mobile' => $doctor->mobile,
                'address' => $doctor->address,
                'balance' => $doctor->balance,
                'image' => $doctor->image,
                'qualification' => $doctor->qualification,
                'education_details' => $doctor->educationDetails,
                'experience_details' => $doctor->experienceDetails,
                'social_icons' => $doctor->socialIcons,
                'speciality' => $doctor->speciality,
                'about' => $doctor->about,
                'slot_type' => $doctor->slot_type,
                'is_favourite' => $check ? true : false,
                'serial_or_slot' => $doctor->serial_or_slot,
                'start_time' => $doctor->start_time,
                'end_time' => $doctor->end_time,
                'serial_day' => $doctor->serial_day,
                'max_serial' => $doctor->max_serial,
                'duration' => $doctor->duration,
                'fees' => $doctor->fees,
                'department_id' => $doctor->department_id,
                'location_id' => $doctor->location_id,
                'featured' => $doctor->featured,
                'country_id' => $doctor->country_id,
                'state_id' => $doctor->state_id,
                'city_id' => $doctor->city_id,
                'item_postal_code' => $doctor->item_postal_code,
                'item_lat' => $doctor->item_lat,
                'item_lng' => $doctor->item_lng,
                'item_price' => $doctor->item_price,
                'item_website' => $doctor->item_website,
                'item_social_facebook' => $doctor->item_social_facebook,
                'item_social_twitter' => $doctor->item_social_twitter,
                'item_social_linkedin' => $doctor->item_social_linkedin,
                'item_social_whatsapp' => $doctor->item_social_whatsapp,
                'item_social_instagram' => $doctor->item_social_instagram,
                'status' => $doctor->status,
                'todayAvailable' => $doctor->todayAvailable,
                'tommorowAvailable' => $doctor->tommorowAvailable,
            ]);
        }
        $lastPage = intval(ceil($data['featuredDoctors']->count()/15));
        $perPage = 15;
        $page = request('page', 1);
        $data['currentPage'] = (int)$page;
        $data['lastPage'] = $lastPage;
        $paginatedData = $data['featuredDoctors']->forPage($page, $perPage);
        $data['featuredDoctors'] = $paginatedData->values();
        return response()->json(['success' => true, 'data' => $data], 200);
    }
}

