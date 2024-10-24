<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Department;
use App\Models\Hospital;
use App\Models\Location;
use App\Models\Country;
use App\Models\Cities;
use App\Models\Doctor;
use App\Models\Staff;
use App\Models\States;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class HospitalsController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Hospitals';
        $hospitals = $this->commonQuery()->paginate(getPaginate());
        return view('admin.hospital.index', compact('pageTitle', 'hospitals'));
    }

    protected function commonQuery(){
        return Hospital::searchable(['name', 'phone', 'hospital_owner'])->filter(['phone']);
    }

    public function form()
    {
        $pageTitle = 'Add New Hospital';
        $departments = Department::orderBy('name')->get();
        $locations   = Location::orderBy('name')->get();
        $countries   = Country::all();
        $states      = States::all();
        return view('admin.hospital.form', compact('pageTitle','states','countries','departments','locations'));
    }

    public function detail($id)
    {  
        $hospitals    = Hospital::findOrFail($id);
        $pageTitle    = 'Hospital Detail - ' . $hospitals->name;
        $departments = Department::orderBy('name')->get();
        $locations   = Location::orderBy('name')->get();
        $countries   = Country::all();
        $states      = States::all();

        return view('admin.hospital.details', compact('pageTitle', 'hospitals','states','departments','locations','countries'));
    }

    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        if ($id) {
            $hospital    = Hospital::findOrFail($id);
            $notification = 'Hospital updated successfully';
        } else {
            $hospital    = new Hospital();
            $notification = 'Hospital added successfully';
        }

        $this->hospitalSave($hospital, $request);
        
        $notify[] = ['success', $notification];
        return to_route('admin.hospital.detail', $hospital->id)->withNotify($notify);
    }

    protected function hospitalSave($hospital, $request)
    {
        if ($request->hasFile('logo')) {
            try {
                $old = $hospital->logo;
                $hospital->logo = fileUploader($request->logo, getFilePath('hospital'), getFileSize('hospital'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $general = gs();
       
            $hospital->name                           = $request->name;
            $hospital->phone                          = $request->phone;
            $hospital->adress                         = $request->adress;
            $hospital->city                           = $request->city;
            $hospital->country                        = $request->country;
            $hospital->state                          = $request->state;
            $hospital->department                     = implode(",",$request->department);
            $hospital->hospital_owner                 = $request->hospital_owner;
            $hospital->hospital_owner_phone           = $request->hospital_owner_phone;
            $hospital->meta_title                     = $request->meta_title;
            $hospital->meta_keywords                  = $request->meta_keywords;
            $hospital->meta_description               = $request->meta_description;
            $hospital->save();
    }
    protected function validation($request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'logo'     => ["$imageValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'name'     => 'required|string|max:40|min:6|unique:hospitals,name,' . $id,
            'phone'                 => 'required|string|max:25',
            'adress'                => 'required',
            'city'                  =>  'required',
            'country'               =>  'required',
            'state'                 =>  'required',
            'department'            =>  'required',
            'hospital_owner'          =>  'required',
            'hospital_owner_phone'    =>  'required'
        ]);
    }
}
