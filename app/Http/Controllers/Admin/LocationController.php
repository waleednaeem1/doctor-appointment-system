<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Listings;
use App\Models\Location;
use App\Models\States;
use App\Models\Cities;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function countries()
    {
        $pageTitle = 'Countries';
        $allCountries   = Country::searchable(['name'])->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('admin.location.country', compact('pageTitle', 'allCountries'));
    }
    public function states()
    {
        $pageTitle = 'States';
        $allStates   = States::searchable(['name','country_id','flag'])->orderBy('name', 'ASC')->paginate(getPaginate());
        return view('admin.location.states', compact('pageTitle', 'allStates'));
    }
    public function cities()
    {   
        $pageTitle = 'Cities';
        $allCities   = Cities::searchable(['city_name','city_state'])->orderBy('city_name', 'ASC')->paginate(getPaginate());
        return view('admin.location.cities', compact('pageTitle', 'allCities'));
    }
    
    public function form()
    {
        $pageTitle   = 'Add New City';
        $countries   = Country::where('id',233)->first();
        $states      = States::where('country_id',233)->get();
        
        return view('admin.location.form', compact('pageTitle','states'));
    }
    public function store(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        if ($id) {
            $cities             = Cities::findOrFail($id);
            $notification       = 'City updated successfully';
        } else {
            $cities             = new Cities();
            $notification       = 'City added successfully';
        }

        $this->citySave($cities, $request);

        
        $notify[] = ['success', $notification];
        return to_route('admin.location.cities')->withNotify($notify);
    }
    protected function validation($request, $id = 0)
    {
        $stateValidation        = $id ? 'nullable' : 'required';
        $cityValidation         = $id ? 'nullable' : 'required';
        $citylngValidation      = $id ? 'nullable' : 'required';
        $request->validate([
            
            'city_name'     => 'required|string|max:100',
            'state_id'      => $stateValidation,
            'city_lat'      => $cityValidation,
            'city_lng'      => $citylngValidation,
            
        ]);
    }

    protected function citySave($city, $request)
    {
        
        $city->state_id     = $request->state_id;
        $city->city_name    = $request->city_name;
        if($request->has('city_lat')){
            $city->city_lat     = $request->city_lat;
        }
        if($request->has('city_lng')){
        $city->city_lng     = $request->city_lng;
        }
        $city->save();
        
    }

    public function formstate()
    {
        $pageTitle   = 'Add New State';
        return view('admin.location.formstate', compact('pageTitle'));
    }
    
    public function storestate(Request $request, $id = 0)
    {
        $this->validationstate($request, $id);

        if ($id) {
            $states             = States::findOrFail($id);
            $notification       = 'State updated successfully';
            
        } else {
            $states                 = new States();
            $notification       = 'State added successfully';
            
        }

        $this->stateSave($states,$request);

        
        $notify[] = ['success', $notification];
        return to_route('admin.location.states')->withNotify($notify);
    }
    protected function validationstate($request, $id = 0)
    {
        
        $request->validate([
            
            'name'     => 'required|string|max:40|min:6|unique:states,name,'.$id,
           
            
        ]);
    }

    protected function stateSave($states,$request)
    {
        
        $states->name                   = $request->name;
        $states->country_id             = 233;
        $states->save();
        
        
    }
        
    
}
