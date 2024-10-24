<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\PetType;
use App\Models\Favorite;
use App\Models\UserPets;
use App\Models\Location;
use App\Models\Country;
use App\Models\States;
use App\Models\Cities;
use App\Models\FeeStructure;
use App\Models\PetDiseaseOnTypeBasis;
use App\Models\VetReviews;
use App\Traits\AppointmentManager;
use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\File; // Import the File facade
use Illuminate\Support\Facades\Input; // Import the Input facade
use Illuminate\Support\Facades\Storage; // Import the Storage facade
// use Stevebauman\Location\Facades\Location;

class DoctorAppointmentController extends Controller
{
    use AppointmentManager,Searchable;

    public function doctors(Request $request)
    {
        $pageTitle   = 'Our Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $cities      = Cities::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $petIds = PetDiseaseOnTypeBasis::pluck('pet_type_id')->unique()->toArray();
        $species = PetType::whereIn('id', $petIds)->orderBy('id', 'DESC')->get();
        $pageType   = '';

        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        // $doctors     = Doctor::active()->with('location');
        // Doctor::orderBy('id', 'DESC')->get(['id','name']);
        $doctors  = Doctor::with('location');
        $doctors->where('status',1)->where('email_verified_at','<>',null);
        if ($request->country || $request->state_id || $request->city_id || $request->postal_code) {
            $doctors = $doctors->when($request->country, function ($query) use ($request) {
                return $query->where('country_id', $request->country);
            })
            ->when($request->state_id, function ($query) use ($request) {
                $state = States::where(['country_id' => $request->country, 'iso2' => $request->state_id])->first();
                $state_id = $state ? $state->id : null;

                return $query->orWhere('state_id', $state_id);
            })
            // ->when($request->city_id, function ($query) use ($request) {
            //     // Uncomment the following lines once you have the correct relationship
            //     $city = Cities::where(['city_name' => $request->city_id, 'state_id' => $state_id])->first();
            //     $city_id = $city ? $city->id : null;

            //     // Update the following line once you have the correct relationship
            //     return $query->orWhere('city_id', $city_id);
            // })
            ->when($request->postal_code, function ($query) use ($request) {
                return $query->orWhere('item_postal_code', $request->postal_code);
            });
        }

        // if ($request->state_id) {
        //     $doctors = $doctors->where('state_id', $request->state);
        // }
        if ($request->species) {
            $doctors = $doctors->where('pet_type_id', $request->species);
        }
        if ($request->department) {
            $doctors = $doctors->where('department_id', $request->department);
        }
        if ($request->doctor) {
            $doctors = $doctors->where('id', $request->doctor);
        }

        $doctors = $doctors->orderBy('id', 'DESC')->searchable(['state_id', 'pet_type_id', 'department_id'])->with('department', 'state', 'favorite', 'petType')->paginate(getPaginate())->withQueryString();

        return view($this->activeTemplate  . 'search', compact('pageTitle', 'locations', 'departments', 'doctors','states','cities','species','pageType'));
    }

    function haversine($lat1, $lon1, $lat2, $lon2) {
        $rad = M_PI / 180;
        $lat1 *= $rad;
        $lon1 *= $rad;
        $lat2 *= $rad;
        $lon2 *= $rad;
        $lonDelta = $lon2 - $lon1;
        $a = pow(sin(($lat2 - $lat1) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($lonDelta / 2), 2);
        $c = 2 * asin(sqrt($a));
        $km = 6371 * $c; // Radius of the Earth in kilometers
        return $km;
    }

    public function nearByVetsLocation()
    {
        $pageTitle   = 'Nearby Doctors';
        return view($this->activeTemplate  . 'nearby', compact('pageTitle'));
    }

    public function nearByVets(Request $request)
    {
        $pageTitle   = 'Doctors near you';
        $pageType   = 'nearbyVets';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $petIds = PetDiseaseOnTypeBasis::pluck('pet_type_id')->unique()->toArray();
        $species = PetType::whereIn('id', $petIds)->orderBy('id', 'DESC')->get();

        $userPets = UserPets::where('user_id',auth()->guard('user')->user()->id)->get(array('id','pet_type_id'));
        $petTypeIds = $userPets->pluck('pet_type_id')->toArray();

        if($request->input('radius')){
            $radius = $request->input('radius');
        }
        else{
            $radius = 10; // miles
        }
        if(isset($request->zipcode) && $request->zipcode == 'zipcode'){
            $zipcode = $request->input('autoUserLatitude');
            $response = Http::get("https://api.zippopotam.us/us/{$zipcode}");
            if ($response->successful()) {
                $data = $response->json();
                // $radius = 6442; // on this waleed came
                // $radius = 6442;
                $userLatitude = $response['places'][0]['latitude'];
                $userLongitude = $response['places'][0]['longitude'];
            } else {
                return redirect()->back()->with('error', 'ZIP code not found or not supported.');
            }
        }
        else{
            // $userLatitude = 28.6235;
            // $userLongitude = 81.4247;
            // $userLatitude = 29.6235;
            // $userLongitude = 81.4247;
            $userLatitude = $request->autoUserLatitude;
            $userLongitude = $request->autoUserLongitude;
        }

        $doctors = DB::table('doctors')
        ->select('doctors.*')
        ->selectRaw('(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(item_lat)) * COS(RADIANS(item_lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(item_lat)))) AS distance', [$userLatitude, $userLongitude, $userLatitude])
        ->whereRaw('(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(item_lat)) * COS(RADIANS(item_lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(item_lat))) < ?)', [$userLatitude, $userLongitude, $userLatitude, $radius])
        ->whereIn('pet_type_id', $petTypeIds)
        ->orderBy('distance')
        ->get();

        if(!isset($doctors) && count($doctors) <= 0){
            $doctors = DB::table('doctors')
            ->select('doctors.*')
            ->selectRaw('(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(item_lat)) * COS(RADIANS(item_lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(item_lat)))) AS distance', [$userLatitude, $userLongitude, $userLatitude])
            ->whereRaw('(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(item_lat)) * COS(RADIANS(item_lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(item_lat))) < ?)', [$userLatitude, $userLongitude, $userLatitude, $radius])
            ->orderBy('distance')
            ->get();
        }
        return view($this->activeTemplate  . 'search', compact('pageTitle', 'locations', 'departments', 'doctors','states','species','pageType','userLatitude','userLongitude','radius'));
    }

    public function addToFavorite($doctorId)
    {
        $user = auth()->guard('user')->user()->id;
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

    public function favoriteDoctorList(Request $request)
    {
        $pageTitle   = 'Favorite Doctors';
        $userId = auth()->guard('user')->user()->id;
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();

        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active();

        if ($request->location) {

            $doctors = $doctors->where('location_id', $request->location);
        }
        if ($request->department) {
            $doctors = $doctors->where('department_id', $request->department);
        }

        $doctors     = $doctors->orderBy('id', 'DESC')
                               ->with('department', 'location', 'favorite')
                               ->whereHas('favorite', function ($query) use ($userId) {
                                    $query->where('user_id', $userId);
                               })
                               ->paginate(getPaginate());

        return view($this->activeTemplate  . 'favorite', compact('pageTitle', 'locations', 'departments', 'doctors','states'));
    }

    public function locations($location)
    {
        $pageTitle   = 'Location wise Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $species      = PetType::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active()->where('location_id', $location)->orderBy('id', 'DESC')->with('department', 'location')->paginate(getPaginate());
        return view($this->activeTemplate . 'search', compact('pageTitle', 'locations', 'departments', 'doctors','states', 'species'));
    }

    public function states($state)
    {
        $pageTitle   = 'State wise Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active()->with('favorite')->where('state_id', $state)->orderBy('id', 'DESC')->with('state','location','department')->paginate(getPaginate());
        $species      = PetType::orderBy('id', 'DESC')->whereHas('doctors')->get();
        return view($this->activeTemplate . 'search', compact('pageTitle', 'locations', 'departments', 'doctors','states','species'));
    }

    public function departments($department)
    {
        $pageTitle   = 'Department wise Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active()->with('favorite')->where('department_id', $department)->orderBy('id', 'DESC')->with('department', 'location')->paginate(getPaginate());
        $species      = PetType::orderBy('id', 'DESC')->whereHas('doctors')->get();
        return view($this->activeTemplate . 'search', compact('pageTitle', 'locations', 'departments', 'doctors','states','species'));
    }

    public function featured()
    {
        $pageTitle   = 'All featured Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active()->where('featured', Status::YES)->orderBy('id', 'DESC')->with('department', 'location','favorite')->paginate(getPaginate());
        $species      = PetType::orderBy('id', 'DESC')->whereHas('doctors')->get();
        return view($this->activeTemplate . 'search', compact('pageTitle',  'locations', 'departments', 'doctors', 'states','species'));
    }

    public function booking($id = 0,$vetid=0)
    {
        $speciesArray = array();
        $getPetType = PetDiseaseOnTypeBasis::where(['doc_id' => $id])->get();
        foreach($getPetType as $petType){
            $petSpecie = PetType::find($petType->pet_type_id);
            array_push($speciesArray,$petSpecie->name);
        }
        $speciesString = implode(', ', $speciesArray);
        $loggedInUser  = auth()->guard('user')->user();
        if(isset($loggedInUser) && $loggedInUser !==''){
            $vetReviews = VetReviews::where(['doctor_id' => $id, 'user_id' => $loggedInUser->id])->with('user')->get();
        }
        $doctor = Doctor::findOrFail($id);
        $userPets   = UserPets::orderBy('id', 'ASC')->where('user_id', @auth()->guard('user')->user()->id)->get();
        $departmentIds = array_map('intval', explode(',', $doctor->department_id));
        $relatedDoctors = Doctor::whereNotIn('id', [$id])->whereIn('department_id', $departmentIds)->with('department')->get();
        $petType =  PetType::where('status',1)->get();
        $backulr =  substr(url()->previous(),-6);

        if(isset($doctor) && $doctor->country_id || $doctor->state_id){
            $getCountryName = Country::where('id',$doctor->country_id)->select('id','name')->first();
            $getStateName = States::where('id',$doctor->state_id)->select('id','name')->first();
        }

        if($vetid !=0){
            Session::put('vetid',$vetid);
        }
        if( Session::get('petId')!=null){
            $ptid   = Session::get('petId');
            $upet = UserPets::where('id',$ptid)->get();
            Session::put('ptname',$upet[0]->name);
        }

        if (!$doctor->status) {
            $notify[] = ['error', 'This doctor is inactive!'];
            return to_route('doctors.all')->withNotify($notify);
        }

        $pageTitle = $doctor->name . ' - Booking';
        $availableDate = [];
        $date = Carbon::now();
        for ($i = 0; $i < $doctor->serial_day; $i++) {
            array_push($availableDate, date('Y-m-d', strtotime($date)));
            $date->addDays(1);
        }
        if(isset($loggedInUser) && $loggedInUser !==''){
            return view($this->activeTemplate . 'booking',  compact('loggedInUser','availableDate','userPets', 'doctor','petType', 'pageTitle', 'relatedDoctors','backulr','vetid','speciesString','getCountryName','getStateName', 'vetReviews'));
        }
        else{
            return view($this->activeTemplate . 'booking',  compact('loggedInUser','availableDate','userPets', 'doctor','petType', 'pageTitle', 'relatedDoctors','backulr','vetid','speciesString','getCountryName','getStateName'));
        }
    }

    public function feesCheck(Request $request)
    {
        $doctor = Doctor::active()->find($request->doctor_id);
        $feeStructures = FeeStructure::where('doctor_id', $request->doctor_id)->get();
        $doctorFees = $doctor->fees;

        foreach ($feeStructures as $feeStructure) {
            $startTime = Carbon::parse($feeStructure->start_time);
            $endTime = Carbon::parse($feeStructure->end_time);
            $totalMinutes = $endTime->diffInMinutes($startTime);
            $totalSlot = $totalMinutes / $doctor->duration;
            $serialOrSlot = [];
            for ($i = 1; $i <= $totalSlot; $i++) {
                array_push($serialOrSlot, date('h:i:a', strtotime($startTime)));
                $startTime->addMinutes($doctor->duration);
            }
            if (in_array($request->time, $serialOrSlot)) {
                $data['fees'] = $feeStructure->fees;
                break;
            }
        }
        $data['fees'] = $data['fees'] ?? $doctorFees;

        return response()->json(@$data);
    }
}
