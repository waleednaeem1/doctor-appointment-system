<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Constants\Status;

//Doctor
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Clinics;
use App\Models\Department;
use App\Models\States;
use App\Models\Country;
use App\Models\Frontend;
use App\Models\Favorite;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $departmentArray = array();
        $userId = $request->user_id;
        $data['appointment'] = Appointment::whereHas('pet', function($q) use ($userId)
        {
            $q->whereHas('user', function($q) use ($userId)
            {
                $q->where('id', $userId);
            });
        })->latest()->where('try', Status::YES)->where('is_delete', Status::NO)->get();
        // $data['clinics'] = Clinics::with('clinicDoctors.educationDetails','clinicDoctors.reviews', 'clinicDoctors.experienceDetails', 'clinicDoctors.socialIcons')->take(5)->get();
        // $data['department'] = Department::all();
        $todayDate = Carbon::today()->format('Y-m-d');
        $tomorrowDate = Carbon::tomorrow()->format('Y-m-d');
        $dates = [$todayDate, $tomorrowDate];

        $clinics = Clinics::with('clinicDoctors.educationDetails', 'clinicDoctors.reviews', 'clinicDoctors.experienceDetails', 'clinicDoctors.socialIcons')->take(5)->get();

        foreach ($clinics as $clinic) {
            foreach ($clinic->clinicDoctors as $doctor) {
                $check = Favorite::where('doctor_id', $doctor->id)
                ->where('user_id', $userId)
                ->exists();

                $isClinicAvailableToday = false;
                $isClinicAvailableTomorrow = false;

                foreach ($dates as $date) {
                    if ($doctor->weekday && in_array($date, json_decode($doctor->weekday))) {
                        $collection = Appointment::hasDoctor()
                            ->where('doctor_id', $doctor->id)
                            ->where('try', Status::YES)
                            ->where('is_delete', Status::NO)
                            ->whereDate('booking_date', $date)
                            ->get();

                        $isClinicAvailable = true;

                        foreach ($collection as $value) {
                            if (in_array($value->time_serial, $doctor->serial_or_slot)) {
                                $isClinicAvailable = false;
                                break;
                            }
                        }

                        if ($date === $todayDate) {
                            $isClinicAvailableToday = $isClinicAvailable;
                        } elseif ($date === $tomorrowDate) {
                            $isClinicAvailableTomorrow = $isClinicAvailable;
                        }
                    }
                }

                // $doctorClinicData = $doctor->toArray();
                // $doctorClinicData['is_favourite'] = $check ? true : false;
                // $doctorClinicData['todayAvailable'] = $isClinicAvailableToday;
                // $doctorClinicData['tomorrowAvailable'] = $isClinicAvailableTomorrow;

                $doctor->is_favourite = $check ? true : false;
                $doctor->todayAvailable = $isClinicAvailableToday;
                $doctor->tomorrowAvailable = $isClinicAvailableTomorrow;
                
                $stateDetail = States::where('id', $clinic->state)->first(array('id','name'));
                $countryDetail = Country::where('id', $clinic->country)->first(array('id','name'));
                
                $departmentIds = explode(',', $clinic->department);
                $departments = Department::whereIn('id', $departmentIds)->get(array('id','name'));
                foreach($departments as $department){
                    array_push($departmentArray,$department->name);
                }
                $departmentString = implode(', ', $departmentArray);

                if(isset($countryDetail->name) && $countryDetail->name !=='')
                    $clinic->country = $countryDetail->name;
                if(isset($stateDetail->name) && $stateDetail->name !=='')
                    $clinic->state = $stateDetail->name;
                if(isset($departmentString) && $departmentString !=='')
                    $clinic->department = $departmentString;

                // You might want to modify this part based on your actual structure
                // $clinic->clinicDoctors->push($doctorClinicData);
                // $clinic->clinicDoctors->is_favourite = $check ? true : false;
                // $clinic->clinicDoctors->todayAvailable = $isClinicAvailableToday;
                // $clinic->clinicDoctors->tomorrowAvailable = $isClinicAvailableTomorrow;
            }
        }
        $data['clinics'] = $clinics;
        $data['elements'] = Frontend::where('data_keys', 'banner.element')->orderBy('id')->orderBy('id','desc')->get();
        $doctors = Doctor::with('reviews', 'educationDetails', 'experienceDetails', 'socialIcons')
            ->active()
            ->orderBy('id', 'DESC')
            ->take(15)
            ->get();

        $data['veterinarians'] = new Collection();

        foreach ($doctors as $doctor) {
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

            $data['veterinarians']->push($doctorData);
        }
        return response()->json(['Success' => true, 'data' => $data], 200);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:subscribers',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()], 403);
        }
        $data['email'] = $request->email;

        $checkEmail = User::subscription($data);
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();
        $notify[] = ['success', 'Subscribed Successfully'];
        return response()->json(['Success' => true, 'msg' => 'You have successfully subscribed'], 200);
    }
}
