<?php

namespace App\Http\Controllers\User;

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
    public function clinics(Request $request)
    {
        $pageTitle = 'Our Clinics';
        $states      = States::orderBy('id', 'DESC')->whereHas('clinics')->get();
        $clinics = Clinics::select('*');
        if ($request->states) {
            $clinics = $clinics->where('state', $request->states);
        }
        if ($request->name) {
            $clinics = $clinics->where('name', $request->name);
        }
        $clinics = $clinics->with('state')->paginate(getPaginate());
        $allclinics = Clinics::all();
        return view($this->activeTemplate  . 'clinics', compact('pageTitle', 'clinics', 'states', 'allclinics'));
    }
    public function details($id)
    {
        $pageTitle = 'Clinic Detail';
        $clinic = Clinics::find($id);
        $departmentIds = explode(',', $clinic->department);
        $departments = Department::whereIn('id', $departmentIds)->get();
        $doctorIds = explode(',', $clinic->doctor_id);
        $doctors = Doctor::whereIn('id', $doctorIds)
            ->with('clinicsViseDepartment')
            ->paginate(getPaginate());

        $state = States::where('id', $clinic->state)->first();
        $country = Country::where('id', $clinic->country)->first();

        return view($this->activeTemplate  . 'clinics_details', compact('pageTitle', 'clinic', 'state', 'departments', 'country', 'doctors'));
    }
    public function doctorSearch(Request $request)
    {
        $pageTitle = 'Clinic Detail';
        $clinic = Clinics::find($request->id);
        $departmentIds = explode(',', $clinic->department);
        $departments = Department::whereIn('id', $departmentIds)->get();
        $doctorIds = explode(',', $clinic->doctor_id);

        // Use Doctor::whereIn() to retrieve the doctors and then paginate the result.
        $doctorsQuery = Doctor::whereIn('id', $doctorIds)->with('clinicsViseDepartment');

        if ($request->name) {
            $doctorsQuery = $doctorsQuery->where('name', $request->name);
        }

        // Use ->paginate() to paginate the results.
        $doctors = $doctorsQuery->paginate(getPaginate());

        $state = States::where('id', $clinic->state)->first();
        $country = Country::where('id', $clinic->country)->first();

        return view($this->activeTemplate  . 'clinics_details', compact('pageTitle', 'clinic', 'state', 'departments', 'country', 'doctors'));
    }
    protected function commonQuery(){
        return Clinics::orderBy('id', 'DESC')->searchable(['name', 'phone', 'clinic_owner'])->filter(['phone']);
    }

}
