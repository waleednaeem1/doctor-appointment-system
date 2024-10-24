<?php

namespace App\Http\Controllers\Api;

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
    public function clinics()
    {
        $data['pageTitle'] = 'Our Clinics';
        $data['clinics'] = Clinics::with('clinicDoctors')->paginate();
        if($data['clinics']->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Clinics not found', 'data' => []], 200);
        }

        foreach($data['clinics'] as $clinic){
            $stateDetail = States::where('id', $clinic->state)->first(array('id','name'));
            $countryDetail = Country::where('id', $clinic->country)->first(array('id','name'));
            $departmentDetail = Department::where('id', $clinic->department)->first(array('id','name'));

            $clinic->state = $stateDetail->name ?? null;
            $clinic->country = $countryDetail->name ?? null;
            $clinic->department = $departmentDetail->name ?? null;
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }
    public function details($id)
    {
        $data['pageTitle'] = 'Clinic Detail';
        $data['clinic'] = Clinics::find($id);
        if(!$data['clinic']){
            return response()->json(['success' => false, 'msg' => 'Clinic not found.', 'data' => []], 200);
        }
        $departmentIds = explode(',', $data['clinic']->department);
        $data['departments'] = Department::whereIn('id', $departmentIds)->get();
        $doctorIds = explode(',', $data['clinic']->doctor_id);
        $data['doctors'] = Doctor::whereIn('id', $doctorIds)->with('clinicsViseDepartment')->get();
        $data['state'] = States::where('id', $data['clinic']->state)->first();
        $data['country'] = Country::where('id', $data['clinic']->country)->first();
        return response()->json(['success' => true, 'data' => $data], 200);
    }
    protected function commonQuery(){
        return Clinics::orderBy('id', 'DESC')->searchable(['name', 'phone', 'clinic_owner'])->filter(['phone']);
    }

}
