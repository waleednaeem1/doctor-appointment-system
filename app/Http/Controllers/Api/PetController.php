<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;
use App\Models\UserPets;
use App\Models\PetAttachment;
use App\Models\PetDisease;
use App\Models\PetDiseaseOnTypeBasis;
use App\Models\PetType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class PetController extends Controller
{
    public function myPets(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $userPets = UserPets::with(['attachments','pettype'])->where('user_id', $request->user_id)->get();
        if ($userPets->isEmpty()) {
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        $userPetsdata = [];
        foreach($userPets as $userPet){
            $petattachments = [];
            $previousRecord = [];
            $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $userPet->created_at)->format('d-m-Y, h:i a');
            foreach($userPet->attachments as $petAttachment){
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
            $userPetsdata[] = (object)[
                'id' => $userPet->id,
                'user_id' => $userPet->user_id,
                'name' => $userPet->name,
                'age' => $userPet->age,
                'age_in' => $userPet->age_in,
                'breed' => $userPet->breed,
                'gender' => $userPet->gender,
                'weight' => $userPet->weight,
                'unit' => $userPet->unit,
                'species' => $userPet->pettype ? $userPet->pettype->name : null,
                'slug' => $userPet->slug,
                'short_description' => $userPet->short_description,
                'status' => $userPet->status,
                'pet_type_id' => $userPet->pet_type_id,
                'meta_title' => $userPet->meta_title,
                'meta_keywords' => $userPet->meta_keywords,
                'meta_description' => $userPet->meta_description,
                'created_at' => $created_at,
                'updated_at' => $userPet->updated_at,
                'attachments' => $petattachments,
                'previous_record' => $previousRecord,
            ];
        }
        return response()->json(['success' => true, 'data' => $userPetsdata], 200);
    }

    public function myPetSaved(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'pet_name' => 'required|string|max:255',
            'pet_age' => 'required',
            'age_unit' => 'required|in:month,year',
            'user_id' => 'required|exists:users,id',
            'pet_type_id' => 'required|exists:pet_type,id',
            'weight' => 'required',
            'weight_unit' => 'required|in:lbs,kg',
            'gender' => 'required|in:male,female',
            // 'image_1' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }

        $notification       = 'Pet added successfully';
        $user_id = $request->user_id;
        $user_pets = new UserPets();
        $user_pets->user_id = $user_id;
        $user_pets->name = $request->pet_name;
        $user_pets->age = $request->pet_age;
        $user_pets->age_in = $request->age_unit;
        $user_pets->breed = $request->breed;
        $user_pets->weight = $request->weight;
        $user_pets->unit = $request->weight_unit;
        $user_pets->gender = $request->gender;
        $user_pets->pet_type_id = $request->pet_type_id;
        $user_pets->short_description = $request->short_description;
        $user_pets->save();
        if ($request->hasFile('image_1')) {
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^image_\d+$/', $key) && $request->hasFile($key)) {
                    try {
                        $file = $request->file($key);
                        $image = fileUploader($file, getFilePath('pets'), getFileSize('pets'), @$user_pets->images);
                        $PetAttachment = new PetAttachment();
                        $PetAttachment->pet_id = $user_pets->id;
                        $PetAttachment->user_id = $user_id;
                        $PetAttachment->attachment_type = 'image';
                        $PetAttachment->attachment = $image;
                        $PetAttachment->save();
                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Couldn\'t upload image'];
                        return response()->json(['message' => 'not image uploaded '], 301);
                    }
                }
            }
        }
        // if ($request->hasFile('videos')) {
        //     try
        //     {
        //         foreach($request->file('videos') as $key => $file) {
        //             $video = fileUploader($file, getFilePath('pets'), getFileSize('pets'), @$user_pets->video);
        //             $PetAttachment = new PetAttachment();
        //             $PetAttachment->pet_id = $user_pets->id;
        //             $PetAttachment->user_id = $user_id;
        //             $PetAttachment->attachment_type = 'video';
        //             $PetAttachment->attachment = $video;
        //             $PetAttachment->save();
        //         }
        //     }
        //     catch (\Exception $exp)
        //     {
        //         $notify[] = ['error', 'Couldn\'t upload video'];
        //         return response()->json(['message' => 'not upload video'], 301);
        //     }
        // }
        $data = [
            'pet_id' => $user_pets->id,
        ];
        return response()->json(['success' => true, 'message' => $notification, 'data' => $data], 200);
    }

    public function petDetails($id){
        $petdetail = UserPets::with('attachments')->where('id', $id)->first();
        if (!$petdetail) {
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        $data = [
            'petdetail' => $petdetail,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function petType(){
        $petType = PetType::orderBy('id', 'DESC')->get();
        if ($petType->isEmpty()) {
            return response()->json(['success' => false, 'msg' => 'Pet type not found', 'data' => []], 200);
        }
        $data = [
            'petTypes' => $petType,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function petPreviousRecord(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:user_pets,id',
            'previous_record_1' => 'required'
        ]);
        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }

        $notification       = 'Pet previous record added successfully';
        $userPet = UserPets::find($request->pet_id);
        if (!$userPet) {
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        if ($request->hasFile('previous_record_1')) {
            foreach ($request->all() as $key => $value) {
                if (preg_match('/^previous_record_\d+$/', $key) && $request->hasFile($key)) {
                    try
                    {
                        $file = $request->file($key);
                        $record = fileUploader($file, getFilePath('pets'), getFileSize('pets'), @$userPet->previous_record);
                        $PetAttachment = new PetAttachment();
                        $PetAttachment->pet_id = $userPet->id;
                        $PetAttachment->user_id = $request->user_id;
                        $PetAttachment->attachment_type = 'previous_record';
                        $PetAttachment->attachment = $record;
                        $PetAttachment->save();

                    }
                    catch (\Exception $exp)
                    {
                        $notify[] = ['error', 'Couldn\'t Previous Recored'];
                        return response()->json(['message' => ' Not Previous Record '], 301);
                    }
                }
            }
        }
        return response()->json(['success' => true, 'message' => $notification], 200);

    }

    public function deletePet(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|exists:users,id',
            'pet_id' => 'required|exists:user_pets,id',
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $user_id = $request->user_id;
        $id = $request->pet_id;
        $UserPet = UserPets::where(['id' => $id, 'user_id' => $user_id])->first();
        if($UserPet){
            $petattachment = PetAttachment::where(['pet_id' => $id, 'user_id' => $user_id])->delete();
            $UserPet->delete();
            return response()->json(['success' => true, 'message' => 'Pet Deleted successfully.'], 200);
        }
        else{
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }

    }

    public function getPetsDisease(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pet_id' => 'required|exists:user_pets,id',
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $id = $request->pet_id;
        $pet = UserPets::find($id);
        if(!$pet){
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        $getPetDises = PetType::where('id', $pet->pet_type_id)->get('pet_disese_id');
        if($getPetDises->isEmpty()){
            return response()->json(['success' => false, 'msg' => 'Pet type not found', 'data' => []], 200);
        }
        $getDises = explode(",",$getPetDises[0]->pet_disese_id);
        $petDisease = PetDisease::whereIn('id',$getDises)->get();
        if($petDisease->isEmpty()){
            return response()->json(['success' => false, 'msg' => 'Pet disease not found', 'data' => []], 200);
        }
        $data = [
            'petDisease' => $petDisease,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function getPetsDiseaseDoctors(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pet_id' => 'required|exists:user_pets,id',
            'pet_disease_id' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $id = $request->pet_id;
        $pet = UserPets::find($id);
        if(!$pet){
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        $pet_type_id = $pet->pet_type_id;
        $ids =[(int)$pet_type_id];
        $disease_ids = explode(",",$request->pet_disease_id);
        $doctor_ids = [];
        foreach ($disease_ids as $disease_id) {
            $doctor_ids = array_merge($doctor_ids, PetDiseaseOnTypeBasis::where(function($query) use ($disease_id, $ids) {
                $query->whereRaw("FIND_IN_SET(?, disease_id)", [$disease_id])
                    ->where('pet_type_id', $ids);
            })->pluck('doc_id')->toArray());
        }
        $doctor_ids = array_unique($doctor_ids);
        $doctors = Doctor::with('educationDetails','experienceDetails','socialIcons')->whereIn('id', $doctor_ids)->get();
        if($doctors->isEmpty()){
            return response()->json(['success' => false, 'msg' => 'Doctor not found', 'data' => []], 200);
        }
        $userId = $request->user_id;
        $todayDate = Carbon::today()->format('Y-m-d');
        $tomorrowDate = Carbon::tomorrow()->format('Y-m-d');
        $dates = [$todayDate, $tomorrowDate];
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
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function getDiseaseofDoctorPet(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pet_id' => 'required|exists:user_pets,id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);
        if ($validator->fails()){
            return response()->json(['success' => false, 'error' => $validator->messages()], 201);
        }
        $id = $request->pet_id;
        $pet = UserPets::find($id);
        if(!$pet){
            return response()->json(['success' => false, 'msg' => 'Pet not found', 'data' => []], 200);
        }
        $pet_type_id = $pet->pet_type_id;
        $getPetDises = PetDiseaseOnTypeBasis::where(['doc_id' => $request->doctor_id, 'pet_type_id' => $pet_type_id])->pluck('disease_id');
        if($getPetDises->isEmpty()){
            $doctor_ids = PetDiseaseOnTypeBasis::where('pet_type_id', $pet_type_id)->pluck('doc_id')->toArray();
            $doctors = Doctor::active()->with('educationDetails','experienceDetails','socialIcons')->whereIn('id', $doctor_ids)->get();
            if($doctors->isEmpty()){
                return response()->json(['success' => false, 'msg' => 'Suggested Doctors not available', 'data' => []], 200);
            }
            $userId = $request->user_id;
            $todayDate = Carbon::today()->format('Y-m-d');
            $tomorrowDate = Carbon::tomorrow()->format('Y-m-d');
            $dates = [$todayDate, $tomorrowDate];
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
            return response()->json(['success' => true, 'check' => false, 'msg' => 'Selected Doctor not belong to this pet please select from below.', 'data' => $data], 200);
        }
        $getDises = explode(",",$getPetDises[0]);
        $petDisease = PetDisease::whereIn('id',$getDises)->get();
        if($petDisease->isEmpty()){
            return response()->json(['success' => false, 'msg' => 'Pet disease not found', 'data' => []], 200);
        }
        $data = [
            'petDisease' => $petDisease,
        ];
        return response()->json(['success' => true, 'check' => true, 'data' => $data], 200);
    }
}
