<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PetType;
use App\Models\PetDisease;
use App\Models\UserPets;
use App\Models\PetAttachment;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;


class PetsController extends Controller
{
    public function index()
    {
        $pageTitle = 'User Pets';
        //$allListings   = UserPets::with('user')->searchable(['item_title'])->orderBy('created_at', 'DESC')->paginate(getPaginate());
         
        // $allPets = UserPets::all();
        $allPets = UserPets::with(['attachments', 'pettype'])->searchable(['name'])->get();
        return view('admin.userPets.index', compact('pageTitle', 'allPets'));
    }
    public function detailUserPet($id)
    {
        $petDetail = UserPets::find($id);
        $pageTitle = 'Pet Detail - ' . $petDetail->name;
        $petImage = PetAttachment::where(['pet_id' => $petDetail->id,'attachment_type' =>'image'])->first();
        $petspecie = PetType::find($petDetail->pet_type_id);
        return view('admin.userPets.userpetdetail', compact('pageTitle', 'petDetail', 'petImage', 'petspecie'));
    }
    public function deleteUserPet($id)
    {
        UserPets::findOrFail($id)->delete();
        return to_route('admin.pets.index');
    }

    
    public function store(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';

        $request->validate([
            'name'       => 'required|max:40|unique:departments,id,'.$id,
            'details'    => 'required|max:255',
            'image'      => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png', 'webp'])],
        ]);

        if ($id) {
            $department           = UserPets::findOrFail($id);
            $notification       = 'UserPets updated successfully';
        } else {
            $department           = new UserPets();
            $notification       = 'UserPets added successfully';
        }

        if ($request->hasFile('image')) {
            try {
                $department->image = fileUploader($request->image, getFilePath('department'), getFileSize('department'), @$department->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload category image'];
                return back()->withNotify($notify);
            }
        }

        $department->name    = $request->name;
        $department->details = $request->details;
        $department->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    //pet type
    public function form()
    {
        $pageTitle   = 'Add New Pet Type';
        $PetDisease  = PetDisease::all();
        return view('admin.userPets.form', compact('pageTitle','PetDisease'));
        
    }
    public function formDisease()
    {
        $pageTitle   = 'Add New Pet Disease';
        return view('admin.diseasepets.form', compact('pageTitle'));
    }
    public function storeDisease(Request $request, $id = 0){
        $this->validation($request, $id);
        if ($id) {
            $pet_disease        = PetDisease::findOrFail($id);
            $notification = 'Pet updated successfully';
        } else {
            $pet_disease        = new PetDisease();
            $notification = 'Pet added successfully';
        }
        $this->petDiseaseSave($pet_disease, $request);
        
        $notify[] = ['success', $notification];
        return to_route('admin.pets.detail-disease', $pet_disease->id)->withNotify($notify);
    }

    public function storePet(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        if ($id) {
            $pet_type        = PetType::findOrFail($id);
            $notification = 'Pet updated successfully';
        } else {
            $pet_type        = new PetType();
            $notification = 'Pet added successfully';
        }
        $this->petTypeSave($pet_type, $request);
        
        $notify[] = ['success', $notification];
        return to_route('admin.pets.detail', $pet_type->id)->withNotify($notify);
    }

    protected function validation($request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'image'         => ["$imageValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'webp'])],
            'name'          => 'required|string|max:140',
        ]);
    }

    protected function petTypeSave($pet_type, $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = $pet_type->image;
                $pet_type->image = fileUploader($request->image, getFilePath('pets'), getFileSize('pets'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $pet_type->name               = $request->name;
        $pet_type->slug               = slug($request->name);
        $pet_type->pet_disese_id      = implode(",",$request->pet_disese_id);
        
        $pet_type->save();
    }
    protected function petDiseaseSave($pet_disease, $request)
    {
        if ($request->hasFile('image')) {
            try {
                $old = $pet_disease->image;
                $pet_disease->image = fileUploader($request->image, getFilePath('petsdisease'), getFileSize('petsdisease'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $pet_disease->name               = $request->name;
        $pet_disease->slug               = slug($request->name);
        $pet_disease->save();
    }

    public function detail($id)
    {
        $pet_type       = PetType::findOrFail($id);
        $pageTitle      = 'Pet Detail - ' . $pet_type->name;
        $PetDisease     = PetDisease::all();
        return view('admin.userPets.details', compact('pageTitle', 'pet_type','PetDisease'));
    }
    public function delete($id)
    {
        PetType::findOrFail($id)->delete();
        return to_route('admin.pets.listing');
    }
    
    public function detailDisease($id)
    {
        $pet_disease       = PetDisease::findOrFail($id);
        $pageTitle      = 'Pet Detail - ' . $pet_disease->name;
        return view('admin.diseasepets.details', compact('pageTitle', 'pet_disease'));
    }
    public function deleteDisease($id)
    {
        PetDisease::findOrFail($id)->delete();
        return to_route('admin.pets.listing-disease');
    }

    public function allPetType()
    {
        $pageTitle = 'All Pet Type';
        $pet_type  = $this->commonQuery()->paginate(getPaginate());
        return view('admin.userPets.listing', compact('pageTitle','pet_type'));
    }
    public function allPetDisease()
    {
        $pageTitle = 'All Pet Disease';
        $pet_disease  = $this->petDiseaseQuery()->paginate(getPaginate());
        return view('admin.diseasepets.listing', compact('pageTitle','pet_disease'));
    }

    protected function commonQuery()
    {
        return PetType::orderBy('id', 'DESC')->searchable(['name', 'slug'])->filter(['status']);
    }
    protected function petDiseaseQuery()
    {
        return PetDisease::orderBy('id', 'DESC')->searchable(['name', 'slug'])->filter(['status']);
    }

    public function status($id)
    {
        return PetType::changeStatus($id);
    }
    public function statusDisease($id)
    {
        return PetDisease::changeStatus($id);
    }
}
