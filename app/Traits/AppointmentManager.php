<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\Appointment;
use App\Models\AssistantDoctorTrack;
use App\Models\Doctor;
use App\Models\GatewayCurrency;
use App\Models\UserPets;
use App\Models\VetReviews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Exception;
use Twilio\Rest\Client;
use TwilioRestClient;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;


/**
 * All Common functionalities to make an appointment
 */
trait AppointmentManager
{
    public $userType;
    protected $twsid = 'AC7f8d76cc819d8d8d1d1389fc86207d5f';
    protected $twtoken='ef1ed56e533d05d16e4d0abeb5eeaeea';

    public function form()
    {
        $pageTitle = 'Make Appointment';
        $doctors   = Doctor::active()->orderBy('name')->get();
        return view($this->userType . '.appointment.form', compact('pageTitle', 'doctors'));
    }

    public function details(Request $request)
    {
        /**;
         * If making appointment via doctor guard then do not check doctor! use else!
         */
        if ($this->userType == 'doctor') {
            $doctor = Doctor::findOrFail(auth()->guard('doctor')->id());
        } else {
            $request->validate([
                'doctor_id' => 'required|numeric|gt:0',
            ]);
            $doctor = Doctor::findOrFail($request->doctor_id);
        }


        if (!$doctor->serial_or_slot) {
            $notify[] = ['error', 'No available schedule for this doctor!'];
            return back()->withNotify($notify);
        }

        $availableDate = [];
        $date          = Carbon::now();
        for ($i = 0; $i < $doctor->serial_day; $i++) {
            array_push($availableDate, date('Y-m-d', strtotime($date)));
            $date->addDays(1);
        }
        $pageTitle = 'Make Appointment';
        return view($this->userType . '.appointment.booking', compact('doctor', 'pageTitle', 'availableDate'));
    }

    public function availability(Request $request)
    {
        $doctor = Doctor::active()->find($request->doctor_id);
        $collection = Appointment::hasDoctor()->where('doctor_id', $request->doctor_id)->where('try', Status::YES)->where('is_delete', Status::NO)->whereDate('booking_date', Carbon::parse($request->date))->get();

        $todayDate = Carbon::today()->format('Y-m-d');
        $currentTime = Carbon::now();
        if( $todayDate != $request->date )
            $currentTime = Carbon::createFromFormat('h:i:a', '12:00:am');

        $data['unavailableTime'] = collect([]);
        foreach ($doctor->serial_or_slot as $value) {
            $slotTime = Carbon::createFromFormat('h:i:a', $value);
            if($currentTime > $slotTime){
                $data['unavailableTime']->push($value) ;
            }
        }

        $data['value'] = collect([]);
        foreach ($collection as  $value) {
            $data['value']->push($value->time_serial);
        }
        return response()->json(@$data);
    }

    public function store(Request $request, $id)
    {
        $validation = [
            'booking_date'      => 'required',
            'time_serial'       => 'required',
            'name'              => 'required',
            'email'             => 'required',
            'mobile'            => 'required',
            'disease'           => 'required',
            'payment_system'    => 'required',
        ];

        $validator = Validator::make($request->all(), $validation);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!$request['pet'] && Session::get('petId') == 0) {

            if(isset(auth()->guard('user')->user()->id)){
                $notify[] = ["error", "Please select or add a pet before making an appointment."];
            }else{
                $notify[] = ["error", "Please login to select or add a pet before making an appointment."];
            }

            return redirect()->back()->withNotify($notify)->withInput();
        }

        $doctor = Doctor::active()->find($id);
        if (!$doctor) {
            $notify[] = ['error', 'The doctor isn\'t available for the appointment'];
            return back()->withNotify($notify);
        }
        if (!$doctor->serial_or_slot) {
            $notify[] = ['error', 'No available schedule for this doctor'];
            return back()->withNotify($notify);
        }
        $timeSerialCheck = $doctor->whereJsonContains('serial_or_slot', $request->time_serial)->exists();
        if (!$timeSerialCheck) {
            $notify[] = ['error', 'Invalid! Something went wrong'];
            return back()->withNotify($notify);
        }
        $existed = Appointment::where('doctor_id', $doctor->id)->where('booking_date', $request->booking_date)->where('time_serial', $request->time_serial)->where('try', Status::YES)->where('is_delete', Status::NO)->exists();

        if ($existed) {
            $notify[] = ['error', 'This time or serial is already booked. Please try another or time or serial'];
            return back()->withNotify($notify);
        }
        if ($this->userType == 'assistant') {
            $doctorCheck = AssistantDoctorTrack::where('assistant_id', auth()->guard('assistant')->id())->where('doctor_id', $doctor->id)->first();

            if (!$doctorCheck) {
                $notify[] = ['error', 'You don\'t have permission to operate this action'];
                return back()->withNotify($notify);
            }
        }

        $petID = Session::get('petId') == 0 ? $request->pet : Session::get('petId');
        $alreadyAppoint = Appointment::where(['booking_date' => $request->booking_date, 'time_serial' => $request->time_serial, 'user_pets_id' => $petID])->first();
        if($alreadyAppoint){
            $doctorName = $alreadyAppoint->doctor->name;
            $notify[] = ['error', "You already booked an appointment this pet with $doctorName"];
            return back()->withNotify($notify);
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
        $appointment->user_pets_id = $petID;
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
        Session::forget('vetid');
        Session::forget('petId');
        Session::forget('ptname');
        Session::save();

        if ($gateways) {
            $appointment->payment_status = Status::APPOINTMENT_PENDING_PAYMENT;
            $appointment->save();

            $pageTitle = "Appointment Payment Method";
            $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE);
            })->with('method')->orderby('method_code')->get();

            $fees     = $doctor->fees;
            $doctorId = $doctor->id;
            $trx      = $appointment->trx;
            $email    = $request->email;
            $string = $request->mobile;
            $mobile = str_replace([' ', '-', '(', ')'], '', $string);
           
            /**
             * Uncomment below line of code after env configuration of paid twilio account
             */
            // $result = $this->sendSMS($mobile, $doctor,$request->booking_date);
            
            //comment due to account twilio suspend 
            $sms = $this->smsTwilio($mobile, $doctor,$request->booking_date);
            return view($this->activeTemplate . 'user.payment.depositpayment', compact('pageTitle', 'fees', 'doctorId', 'trx', 'email', 'gatewayCurrency'));
        }

        notify($this->notifyUser($appointment), 'APPOINTMENT_CONFIRMATION', [
            'booking_date' => $appointment->booking_date,
            'time_serial'  => $appointment->time_serial,
            'doctor_name'  => $doctor->name,
            'doctor_fees'  => '' . $doctor->fees . ' ' . $general->cur_text . '',
        ]);

        $notify[] = ['success', 'New Appointment make successfully'];
        return redirect('veterinarians/all')->withNotify($notify);
    }
    public function storeReviews(Request $request)
    {
        $data = [
            'doctor_id' => $request->vetId,
            'user_id' => auth()->guard('user')->user()->id,
            'review' => $request->vetReview,
            'rating' => $request->rating,
            'status' => 0,
        ];
        $vetReview = VetReviews::create($data);
        $notify[] = ['success', 'Review added successfully'];
        return back()->withNotify($notify);
    }
    public function sendSMS($mobile, $doctor,$date)
    {
        try {
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_AUTH_TOKEN');
            $number = env('TWILIO_PHONE_NUMBER');

            $client = new Client($account_sid,$account_token);
            $client->messages->create('+1'.$mobile,[
                'from'=>$number,
                'body'=>'Your pet appointment is booked with '. $doctor->name .' at '.$date,
            ]);
            $result = 'Message sent';
            return $result;

        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }

    public function smsTwilio($mobile, $doctor, $date){
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "ACfb3954ae0e36762176da089770d17df8";
        $token = "62b3870052503d93842862d986c5cd2b";
        $client = new \Twilio\Rest\Client($sid, $token);
        $baseUrl = config('app.url');
        $createTwRm = $this->createTwilioRoom();
        $callUrl = $baseUrl.$createTwRm;
        $smsBody = "Your pet appointment is booked with ". $doctor->name ." at ".$date." click to call   ".$callUrl.".You have paid ".$doctor->fees. "for the appointment";
        // Use the Client to make requests to the Twilio REST API
        
        $mobileVet = $doctor->mobile;
       
        //for trial account
        $client->messages->create(
            // The number you'd like to send the message to
            '+15162443827',
            [
                // A Twilio phone number you purchased at https://console.twilio.com
                'from' => '+18447581536',
                // The body of the text message you'd like to send

                "shortenUrls" => True,
                "body" => $smsBody,
                //"body" => "Visit this link to start earning rewards today! https://example.com/N6uAirXeREkpV2MW7kpV2MW7TAvh1zn4gEFMTAvh1zn4gEFMN6uAirXeRE"
            ]
        );

        //end for trail account







        // $client->messages->create(
        //     // The number you'd like to send the message to
        //     $mobileVet,
        //     [
        //         // A Twilio phone number you purchased at https://console.twilio.com
        //         'from' => '+18447581536',
        //         // The body of the text message you'd like to send

        //         "shortenUrls" => True,
        //         "body" => $smsBody,
        //         //"body" => "Visit this link to start earning rewards today! https://example.com/N6uAirXeREkpV2MW7kpV2MW7TAvh1zn4gEFMTAvh1zn4gEFMN6uAirXeRE"
        //     ]
        // );

        // $client->messages->create(
        //     // The number you'd like to send the message to
        //     '+1'.$mobile,
        //     [
        //         // A Twilio phone number you purchased at https://console.twilio.com
        //         'from' => '+18447581536',
        //         // The body of the text message you'd like to send

        //         "shortenUrls" => True,
        //         "body" => $smsBody,
        //         //"body" => "Visit this link to start earning rewards today! https://example.com/N6uAirXeREkpV2MW7kpV2MW7TAvh1zn4gEFMTAvh1zn4gEFMN6uAirXeRE"
        //         ]
        // );
    }

    public function createTwilioRoom()
    {

        $client = new \Twilio\Rest\Client('ACfb3954ae0e36762176da089770d17df8','62b3870052503d93842862d986c5cd2b');
        $roomName = rand(0,99999);
        $exists = $client->video->v1->rooms->read([ 'uniqueName' => $roomName]);

        if (empty($exists)) {
                $client->video->v1->rooms->create([
                'uniqueName' => $roomName,
                'type' => 'go',
                'recordParticipantsOnConnect' => false
            ]);

           // \Log::debug("created new room: ".$request->roomName);
        }

        return 'joinroom/'.$roomName;
    }


    public function done($id)
    {
        $appointment =  Appointment::findOrFail($id);

        if ($appointment->is_complete == Status::APPOINTMENT_INCOMPLETE && $appointment->payment_status != Status::APPOINTMENT_PAID_PAYMENT) {
            $appointment->is_complete = Status::APPOINTMENT_COMPLETE;

            if ($appointment->payment_status == Status::APPOINTMENT_CASH_PAYMENT) {
                $doctor = Doctor::findOrFail($appointment->doctor->id);
                $doctor->balance += $doctor->fees;
                $doctor->save();
                $appointment->payment_status = Status::APPOINTMENT_PAID_PAYMENT;
            }

            $appointment->save();
            $notify[] = ['success', 'Appointed service is done successfully'];
            return back()->withNotify($notify);

        } else {
            $notify[] = ['error', 'Something is wrong!'];
            return back()->withNotify($notify);
        }
    }

    public function remove($id)
    {
        $appointment = Appointment::findOrFail($id);

        // if ($appointment->is_delete || $appointment->payment_status) {
        //     $notify[] = ['error', 'Appointment trashed operation is invalid'];
        //     return back()->withNotify($notify);
        // }

        $appointment->is_delete = Status::YES;

        if ($this->userType == 'admin') {
            $appointment->delete_by_admin = 1;
        } elseif ($this->userType == 'staff') {
            $appointment->delete_by_staff = auth()->guard('staff')->id();
        } elseif ($this->userType == 'doctor') {
            $appointment->delete_by_doctor = auth()->guard('doctor')->id();
        } elseif ($this->userType == 'user') {
            $appointment->delete_by_user = auth()->guard('user')->id();
        } else {
            $appointment->delete_by_assistant = auth()->guard('assistant')->id();
        }
        $appointment->save();
        notify( $this->notifyUser($appointment), 'APPOINTMENT_REJECTION', [
            'booking_date' => $appointment->booking_date,
            'time_serial'  => $appointment->time_serial,
            'doctor_name'  => $appointment->doctor->name
        ]);

        $notify[] = ['success', 'Appointment service is trashed successfully'];
        return back()->withNotify($notify);
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

    protected function detectUserType($appointments)
    {
        if ($this->userType == 'admin' || $this->userType == 'staff') {
            $appointments  = $appointments->hasDoctor();
        } else {
            $appointments->where('doctor_id', auth()->guard('doctor')->id());
        }

        if ($this->userType == 'staff') {
            $appointments  = $appointments->where('added_staff_id', auth()->guard('staff')->id());
        }
        $appointments = $appointments->searchable(['name', 'email', 'disease'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return $appointments;
    }

    public function new()
    {
        $pageTitle    = 'All New Appointments';
        $appointments = Appointment::allAppointment()->wherehas('pet')->with('staff', 'doctor', 'assistant', 'pet','pet.pettype');
        $appointments = $this->detectUserType($appointments);

        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function petappoint()
    {
        $pageTitle    = 'All Appointments';
        $id = auth()->guard('user')->id();

        $appointments = Appointment::allAppointment()->whereHas('pet', function($q) use ($id)
        {
            $q->whereHas('user', function($q) use ($id)
            {
                $q->whereIn('id', array($id));
            });
        })->with('doctor','pet','pet.pettype');

        $appointments = $appointments->searchable(['name', 'email', 'disease'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function doneService()
    {
        $pageTitle    = 'Service Done Appointments';
        $appointments = Appointment::CompleteAllAppointment()->wherehas('pet')->with('staff', 'doctor', 'assistant','pet','pet.pettype');
        $appointments = $this->detectUserType($appointments);

        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function serviceTrashed()
    {
        $pageTitle    = 'Trashed Appointments';
        if($this->userType=='admin'){
            $appointments = Appointment::wherehas('pet')->where('is_delete', Status::YES)->with('doctor','deletedByDoctor','pet','pet.pettype');
        }else{
            $appointments = Appointment::wherehas('pet')->where('is_delete', Status::YES)->with('doctors','deletedByDoctor','pet','pet.pettype');
            // $appointments = Appointment::where('is_delete', Status::YES)->with('deletedByStaff', 'deletedByDoctor', 'deletedByAssistant', 'doctor', 'staff', 'assistant');
        }

        $appointments = $this->detectUserType($appointments);

        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function serviceCancelled()
    {
        $pageTitle    = 'Cancelled Appointments';
        if($this->userType=='admin'){
            $appointments = Appointment::wherehas('pet')->where('is_delete', Status::YES)->with('doctor','deletedByDoctor','pet','pet.pettype');
        }else{
            $appointments = Appointment::wherehas('pet')->where('is_delete', Status::YES)->with('doctors','deletedByDoctor','pet','pet.pettype');
            // $appointments = Appointment::where('is_delete', Status::YES)->with('deletedByStaff', 'deletedByDoctor', 'deletedByAssistant', 'doctor', 'staff', 'assistant');
        }

        $appointments = $this->detectUserType($appointments);

        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function userDoneService()
    {
        $pageTitle    = 'Service Done Appointments';
        $id = auth()->guard('user')->id();

        $appointments = Appointment::CompleteAllAppointment()->whereHas('pet', function($q) use ($id)
        {
            $q->whereHas('user', function($q) use ($id)
            {
                $q->where('id', $id);
            });
        })->with('doctor','pet','pet.pettype');

        $appointments = $appointments->searchable(['name', 'email', 'disease'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }

    public function userServiceTrashed()
    {
        $pageTitle    = 'Trashed Appointments';
        $id = auth()->guard('user')->id();

        $appointments = Appointment::whereHas('pet', function($q) use ($id)
        {
            $q->whereHas('user', function($q) use ($id)
            {
                $q->where('id', $id);
            });
        })->where('is_delete', Status::YES)->with('doctor','pet','pet.pettype');

        $appointments = $appointments->searchable(['name', 'email', 'disease'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view($this->userType . '.appointment.index', compact('pageTitle', 'appointments'));
    }
}
