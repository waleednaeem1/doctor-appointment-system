<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\User\Auth\LoginController;
use App\Mail\Subscription;
use App\Models\AdminNotification;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Feedback;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Location;
use App\Models\Page;
use App\Models\PetAttachment;
use App\Models\PetDisease;
use App\Models\PetDiseaseOnTypeBasis;
use App\Models\PetType;
use App\Models\States;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserPets;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Carbon;

use TwilioRestClient;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
class SiteController extends Controller
{
    use AuthenticatesUsers;
    protected $user = null;
    protected $petAllowedExtension = ['jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx', 'webp'];
    
    
    protected $sid = 'AC21cc5e49279947970f6459b57a3d6979';
    protected $token='824382ea4239e47895e342a2d8155d16';
    protected $key='';
    protected $secret='RM6b8e1fa640b04e9ced66c1f62e00befa';

    // public function __construct()
    // {
    // $this->sid = config('services.twilio.sid');
    // $this->token = config('services.twilio.token');
    // $this->key = config('services.twilio.key');
    // $this->secret = config('services.twilio.secret');
    // }

    
    
    public function index()
    {   
        $pageTitle = 'Home';
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','/')->first();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $cities      = Cities::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $petIds = PetDiseaseOnTypeBasis::pluck('pet_type_id')->unique()->toArray();
        $species = PetType::whereIn('id', $petIds)->orderBy('id', 'DESC')->get();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections','states','species','cities'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }

    public function contact()
    {
        $pageTitle      = "Contact Us";
        $contactCon     = getContent('contact_us.content', true);
        $socialElements = getContent('social_icon.element', false, null, true);
        $sections       = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->firstOrFail();
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'contactCon', 'socialElements', 'sections'));
    }

    public function about()
    {
        $pageTitle      = "About Us";
        $page_content     = Frontend::where('data_keys', 'about.content')->first();
        $sections = Page::where('slug','terms_of_service')->first();
        return view($this->activeTemplate . 'about', compact('pageTitle', 'page_content', 'sections'));
    }

    public function terms_of_service()
    {
        $pageTitle      = "Terms of Service";
        $page_content     = Frontend::where('data_keys', 'terms_of_service.content')->first();
        $sections = Page::where('slug','about')->first();
        return view($this->activeTemplate . 'terms_of_service', compact('pageTitle', 'page_content', 'sections'));
    }

    public function tickets()
    {
        $pageTitle      = "Tickets";
        if(isset(auth()->guard('user')->user()->id)){
            $user_id = auth()->guard('user')->user()->id;
            $items = SupportTicket::where('user_id',$user_id)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        }else{
            $items = SupportTicket::where('user_id',null)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        }
        return view($this->activeTemplate . 'tickets', compact('pageTitle','items'));
    }

    public function privacy_policy()
    {
        $pageTitle      = "Privacy Policy";
        $page_content     = Frontend::where('data_keys', 'privacy_policy.content')->first();
        $sections = Page::where('slug','privacy-policy')->first();
        return view($this->activeTemplate . 'privacy_policy', compact('pageTitle', 'page_content', 'sections'));
    }

    public function faqs()
    {
        $pageTitle      = "FAQ's";
        $faqs     = Frontend::where('data_keys', 'faq.element')->get();
        return view($this->activeTemplate . 'faq', compact('pageTitle', 'faqs'));
    }

    public function myPets()
    {
        $user = auth()->guard('user')->user();
        if($user){
            $userPets =  UserPets::with('attachments')->where('user_id',$user->id)->get();
        }else{
            $userPets =  array();
        }
        $pageTitle         = "My Pets";
        $pet_type          =  PetType::where('status',1)->get();
        return view($this->activeTemplate . 'myPets', compact('pageTitle','userPets','pet_type'));
    }

    public function myPetSaved(Request $request)
    {
        if($request->record_type == 'pet'){
            $notification       = 'Pet added successfully';
            $user_id = auth()->guard('user')->user()->id;
            $user_pets = new UserPets();
            $user_pets->user_id                 = $user_id;
            $user_pets->name                    = $request->name;
            $user_pets->age                     = $request->age;
            $user_pets->age_in                  = $request->age_in;
            $user_pets->breed                   = $request->breed;
            $user_pets->weight                  = $request->weight;
            $user_pets->unit                    = $request->unit;
            $user_pets->gender                  = $request->gender;
            $user_pets->short_description       = $request->short_description;
            $user_pets->pet_type_id             = $request->pet_type_id;
            $user_pets->save();
            if ($request->hasFile('images')) {
                try
                {
                    foreach($request->file('images') as $key => $file) {
                        $image = fileUploader($file, getFilePath('pets'), getFileSize('pets'), @$user_pets->images);
                        $PetAttachment = new PetAttachment();
                        $PetAttachment->pet_id = $user_pets->id;
                        $PetAttachment->user_id = $user_id;
                        $PetAttachment->attachment_type = 'image';
                        $PetAttachment->attachment = $image;
                        $PetAttachment->save();
                    }
                }
                catch (\Exception $exp)
                {
                    $notify[] = ['error', 'Couldn\'t upload image'];
                    return response()->json(['message' => 'not image uploaded '], 301);
                }
            }
            // if ($request->hasFile('video')) {
            //     try
            //     {
            //         foreach($request->file('video') as $key => $file) {
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
            return response()->json(['message' => $notification, 'page_type' => $request->page_type], 200);
        }else{
            $notification       = 'Pet added successfully';
            if ($request->hasFile('previous_record')) {
                if(isset(auth()->guard('user')->user()->id)){
                    $user_id = auth()->guard('user')->user()->id;
                }
                $user_pets = UserPets::where('user_id', $user_id)->latest()->first();
                if(!$user_pets){
                    return response()->json(['success' => false, 'message' => 'Pet not found']);
                }
                try
                {
                    foreach($request->file('previous_record') as $key => $file) {
                        $record = fileUploader($file, getFilePath('pets'), getFileSize('pets'), @$user_pets->previous_record);
                        $PetAttachment = new PetAttachment();
                        $PetAttachment->pet_id = $user_pets->id;
                        $PetAttachment->user_id = $user_id;
                        $PetAttachment->attachment_type = 'previous_record';
                        $PetAttachment->attachment = $record;
                        $PetAttachment->save();
                    }
                    // $user_pets->previous_record = fileUploader($request->previous_record, getFilePath('pets'), getFileSize('pets'), @$user_pets->previous_record);
                }
                catch (\Exception $exp)
                {
                    $notify[] = ['error', 'Couldn\'t Previous Recored'];
                    return response()->json(['success' => false, 'message' => 'Previous Record not uploaded']);
                }
            }
            return response()->json(['success' => true ,'message' => $notification], 200);
        }
    }

    public function getPets($id)
    {
        $userPets = UserPets::with('pettype')->find($id);
        return $userPets;
    }
    
    public function knowledgeBase()
    {
        die("waleed knowledge");
        $user = auth()->guard('user')->user();
        
        return view($this->activeTemplate . 'myPets', compact('pageTitle','userPets','pet_type'));
    }

    public function getAppointmentsPets()
    {
        $user  = auth()->guard('user')->user();
        $pageTitle = "Who is the appointment for?";
        $allPetTypes = PetType::where('status',1)->get();
        if(isset($user) && $user !==''){
            $userPets =  UserPets::with('attachments')->where('user_id',$user->id)->get();
        }
        else
            $userPets = array();
        return view($this->activeTemplate . 'getAppointments', compact('pageTitle','allPetTypes','userPets'));
    }

    public function getAppointmentsHome()
    {
        if(Session::get('vetid') != null){
            session()->put('vetid', null);
        }
        $user  = auth()->guard('user')->user();
        $pageTitle = "Who is the appointment for?";
        $allPetTypes = PetType::where('status',1)->get();
        if(isset($user) && $user !==''){
            $userPets =  UserPets::with('attachments')->where('user_id',$user->id)->get();
        }
        else
            $userPets = array();
        return view($this->activeTemplate . 'getAppointmentsHome', compact('pageTitle','allPetTypes','userPets'));
    }

    public function getPetsDiseaseHome($id,$petId=0)
    {
        if(Session::get('vetid') != null){
            session()->put('vetid', null);
        }
        Session::put('petId', $petId);
        $pageTitle          = 'Pet Disease';
        $getPetDises        = PetType::where('id',$id)->get('pet_disese_id');
        $getDises           = explode(",",$getPetDises[0]->pet_disese_id);
        $petDisease         = PetDisease::whereIn('id',$getDises)->get();
        return view($this->activeTemplate . 'petdiseaselisting',compact('pageTitle','petDisease','id'));
    }

    public function petDetails($id){
        $pageTitle   = "Pet Details";
        $userPet = UserPets::with('pettype')->find($id);
        $imageattachments = $userPet->attachments()->where(['user_id' => auth()->guard('user')->user()->id, 'pet_id' => $userPet->id, 'attachment_type' => 'image'])->get();
        $videoattachments = $userPet->attachments()->where(['user_id' => auth()->guard('user')->user()->id, 'pet_id' => $userPet->id, 'attachment_type' => 'video'])->get();
        $previousrecordattachments = $userPet->attachments()->where(['user_id' => auth()->guard('user')->user()->id, 'pet_id' => $userPet->id, 'attachment_type' => 'previous_record'])->get();
        return view($this->activeTemplate . 'pet_details', compact('userPet', 'pageTitle','imageattachments','videoattachments','previousrecordattachments'));
    }

    public function deleteSinglePetAttachment($id, $type){
        if($type == 'image'){
            PetAttachment::where(['id' => $id, 'attachment_type' => $type])->delete();
            return response()->json(['status' => 'image','message' => 'Image Deleted Successfully'], 200);
        }
        if($type == 'video'){
            PetAttachment::where(['id' => $id, 'attachment_type' => $type])->delete();
            return response()->json(['status' => 'video','message' => 'Video Deleted Successfully'], 200);
        }
        if($type == 'previous_record'){
            PetAttachment::where(['id' => $id, 'attachment_type' => $type])->delete();
            return response()->json(['status' => 'previous_record','message' => 'Previous Record Deleted Successfully'], 200);
        }
    }

    public function contactSubmit(Request $request)
    {
        $user_id   = auth()->guard('user')->user()->id ?? null;
        if($user_id){
            $user_id      = auth()->guard('user')->user()->id;
        }else{
            $user_id      = $this->user;
        }
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withInput()->withNotify($notify);
        }
        //check if guest not ticket saved only send email

        $get_user_id   = auth()->guard('user')->user()->id ?? null;

        if(isset($get_user_id)){
            $random = getNumber();
            $ticket = new SupportTicket();
            $ticket->name = $request->name;
            $ticket->email = $request->email;
            $ticket->priority = Status::PRIORITY_MEDIUM;

            $ticket->user_id = $user_id;
            $ticket->ticket = $random;
            $ticket->subject = $request->subject;
            $ticket->last_reply = Carbon::now();
            $ticket->status = Status::TICKET_OPEN;
            $ticket->save();

            $adminNotification = new AdminNotification();
            $adminNotification->title = 'A new support ticket has opened ';
            $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
            $adminNotification->save();

            $message = new SupportMessage();
            $message->support_ticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();
        }
        $data = $request->all();
        $checkEmail = User::userContact($data);

        if(isset($get_user_id)){
            $notify[] = ['success', 'Ticket created successfully!'];
            return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
        }else{
            $notify[] = ['success', 'Email sent successfully!'];
            return to_route('contact')->withNotify($notify);
        }
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle = 'Blogs';
        $blogs     = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
        $latest    = Frontend::latest()->where('data_keys', 'blog.element')->limit(10)->get();
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->first();
        return view($this->activeTemplate . 'blog', compact('pageTitle', 'blogs', 'latest', 'sections'));
    }

    public function blogDetails($slug, $id)
    {
        $pageTitle   = "Blog Details";
        $blog        = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();

        $blog->views += 1;
        $blog->save();

        $latestPosts = Frontend::latest()->where('data_keys', 'blog.element')->where('id', '!=', $id)->limit(10)->get();

        $seoContents['keywords']           = $blog->meta_keywords ?? [];
        $seoContents['social_title']       = $blog->data_values->title;
        $seoContents['description']        = strLimit(strip_tags($blog->data_values->description_nic), 150);
        $seoContents['social_description'] = strLimit(strip_tags($blog->data_values->description_nic), 150);
        $seoContents['image']              = getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '830x460');
        $seoContents['image_size']         = '830x460';
        return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'latestPosts', 'seoContents'));

    }

    public function login($id=null)
    {
        if(auth()->guard('user')->user()){
            return redirect('/');
        }
        else{
            if($id != null){
                Session::put('dId', $id);
            }
            $pageTitle = 'Login';
            return view($this->activeTemplate . 'login',compact('pageTitle'));
        }
    }

    public function register()
    {
        $pageTitle = 'Register';
        $countries      = Country::all();
        $cities      = Cities::all();
        return view($this->activeTemplate . 'register',compact('pageTitle','cities','countries'));
    }

    public function getPetsDisease($id,$petId=0)
    {
        Session::put('petId', $petId);
        $getSessionVet = Session::get('vetid');
        $diseases = PetDiseaseOnTypeBasis::where(['doc_id' => $getSessionVet, 'pet_type_id' => $id])->first();
        if(!$diseases){
            $doctor_ids = PetDiseaseOnTypeBasis::where('pet_type_id', $id)->pluck('doc_id')->toArray();
            $pageTitle          = 'Doctors';
            $vetName = Doctor::find(Session::get('vetid'))->name;
            $petTypeId = UserPets::with('pettype')->find(Session::get('petId'));
            $petTypeName = $petTypeId->pettype->name;
            $doctors = Doctor::active()->with('department', 'state', 'favorite', 'petType','location')->whereIn('id', $doctor_ids)->latest()->get();
            return view($this->activeTemplate  . 'suggested_doctors', compact('doctors','pageTitle','vetName','petTypeName'));
        }
        $getDises = explode(",", $diseases->disease_id);
        $pageTitle          = 'Pet Disease';
        $petDisease         = PetDisease::whereIn('id',$getDises)->get();
        return view($this->activeTemplate . 'petdiseaselisting',compact('pageTitle','petDisease','id'));

    }

    public function moreVeterinarians()
    {
        $petTypeId = UserPets::with('pettype')->find(Session::get('petId'));
        $id = $petTypeId->pettype->id;
        $doctor_ids = PetDiseaseOnTypeBasis::where('pet_type_id', $id)->pluck('doc_id')->toArray();
        $pageTitle = 'Doctors';
        $vetName = Doctor::find(Session::get('vetid'))->name;
        $petTypeName = $petTypeId->pettype->name;
        $doctors = Doctor::active()->with('department', 'state', 'favorite', 'petType','location')->whereIn('id', $doctor_ids)->latest()->paginate(getPaginate());
        return view($this->activeTemplate  . 'more_doctors', compact('doctors','pageTitle','vetName','petTypeName'));
    }
    
    public function allVeterianSearch(Request $request)
    {
        $pet_type_id = $request->pet_type_id;
        if(Session::get('vetid')!= null){
            $getSessionVet = Session::get('vetid');
            $doctorsCounter = Doctor::where('id',$getSessionVet)->count();
            if($doctorsCounter>0){
                return redirect('veterinarians/booking/'.$getSessionVet);
            }
        }

        $pageTitle   = 'Doctors';
        $locations   = Location::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $states      = States::orderBy('id', 'DESC')->whereHas('doctors')->get();

        $departments = Department::orderBy('id', 'DESC')->whereHas('doctors')->get();
        $doctors     = Doctor::active()->with('location');

        $ids =[(int)$pet_type_id];
        $disease_ids = $request->pet_disese_id;
        $doctor_ids = [];
        foreach ($disease_ids as $disease_id) {
            $doctor_ids = array_merge($doctor_ids, PetDiseaseOnTypeBasis::where(function($query) use ($disease_id, $ids) {
                $query->whereRaw("FIND_IN_SET(?, disease_id)", [$disease_id])
                ->where('pet_type_id', $ids);
            })->pluck('doc_id')->toArray());
        }
        $doctor_ids = array_unique($doctor_ids);
        $doctors = Doctor::whereIn('id', $doctor_ids)->where('status',1)->orderBy('id', 'DESC')->searchable(['pet_type_id','pet_disese_id'])->with('department', 'state', 'favorite','location')->paginate(getPaginate())->withQueryString();

        return view($this->activeTemplate  . 'searchvetlisting', compact('pageTitle', 'locations', 'departments', 'doctors','states'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:subscribers',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        $data['email'] = $request->email;
        $checkEmail = User::subscription($data);
        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->save();
        $notify[] = ['success', 'Subscribed Successfully'];
        return response()->json(['success' => 'You have successfully subscribed']);
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if($imgHeight < 100 && $fontSize > 30){
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        $general = gs();
        if($general->maintenance_mode == Status::DISABLE){
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys','maintenance.data')->first();
        return view($this->activeTemplate.'maintenance',compact('pageTitle','maintenance'));
    }

    public function feedback()
    {
        $pageTitle      = "Feedback";
        $page_content     = Frontend::where('data_keys', 'feedback.content')->first();
        return view($this->activeTemplate . 'feedback', compact('pageTitle', 'page_content'));
    }

    public function feedbackSubmit(Request $request)
    {
        $this->validate($request, [
            'feedback'  => 'required',
            'experience'  => 'required',
        ]);
        $user = auth()->guard('user')->user()->id;
        $feedback = new Feedback();
        $feedback->user_id = $user;
        $feedback->feedback = $request->feedback;
        $feedback->type = $request->type;
        $feedback->experience = $request->experience;
        $feedback->save();

        $notify[] = ['success', 'Feedback sent successfully!'];
        return to_route('feedback')->withNotify($notify);
    }

    public function reportSubmit(Request $request)
    {
        $this->validate($request, [
            'feedback'  => 'required',
        ]);
        $user = auth()->guard('user')->user()->id;
        $feedback = new Feedback();
        $feedback->user_id = $user;
        $feedback->feedback = $request->feedback;
        $feedback->type = $request->type;
        $feedback->save();

        $notify[] = ['success', 'Report sent successfully!'];
        return to_route('feedback')->withNotify($notify);
    }

    public function petImages(Request $request, $id)
    {
        $this->validationimages($request);
        if ($request->hasFile('attachments')) {
            $uploadAttachments = $this->storeImageAttachments($id);
            if ($uploadAttachments != 200) return back()->withNotify($uploadAttachments);;
        }
        $notify[] = ['success', 'Image uploaded successfully!'];
        return back()->withNotify($notify);
    }

    protected function storeImageAttachments($id)
    {
        $path = getFilePath('pets');
        $uid = auth()->guard('user')->user()->id;
        foreach ($this->files as  $file) {
            try {

                $attachment = new PetAttachment();
                $attachment->pet_id = $id;
                $attachment->user_id = $uid;
                $attachment->attachment_type = 'image';
                $attachment->attachment = fileUploader($file, $path);
                $attachment->save();
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not upload'];
                return $notify;
            }
        }
        return 200;
    }

    protected function validationimages($request)
    {
        $maxSize = substr(ini_get('upload_max_filesize'), 0, -1);
        $maxSize = 1;
        $this->maxSize = $maxSize;
        $this->files = $request->file('attachments');

        $request->validate([
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) {
                    foreach ($this->files as $file) {
                        $ext = strtolower($file->getClientOriginalExtension());
                        if (($file->getSize() / 1000000) > $this->maxSize) {
                            return $fail("Maximum $this->maxSize MB file size allowed!");
                        }
                        if (!in_array($ext, $this->petAllowedExtension)) {
                            return $fail("Only png, jpg, jpeg files are allowed");
                        }
                    }
                    if (count($this->files) > 50) {
                        return $fail("Maximum 50 files can be uploaded");
                    }
                },
            ],

        ]);
    }

    //for extra videos tab

    public function petVideos(Request $request, $id)
    {
        $this->files = $request->file('attachmentsvideos');
        if ($request->hasFile('attachmentsvideos')) {
            $uploadAttachments = $this->storeVideoAttachments($id);
            if ($uploadAttachments != 200) return back()->withNotify($uploadAttachments);;
        }
        $notify[] = ['success', 'Video uploaded successfully!'];
        return back()->withNotify($notify);
    }

    protected function storeVideoAttachments($id)
    {
        $path = getFilePath('pets');
        $uid = auth()->guard('user')->user()->id;

        foreach ($this->files as  $file) {
            try {

                $attachment = new PetAttachment();
                $attachment->pet_id = $id;
                $attachment->user_id = $uid;
                $attachment->attachment_type = 'video';
                $attachment->attachment = fileUploader($file, $path);
                $attachment->save();
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Video could not upload'];
                return $notify;
            }
        }

        return 200;
    }


    //for extra old records tab

    public function petRecords(Request $request, $id)
    {
        $this->files = $request->file('attachmentsrecords');
        if ($request->hasFile('attachmentsrecords')) {
            $uploadAttachments = $this->storeRecordAttachments($id);
            if ($uploadAttachments != 200) return back()->withNotify($uploadAttachments);;
        }
        $notify[] = ['success', 'Record uploaded successfully!'];
        return back()->withNotify($notify);
    }

    protected function storeRecordAttachments($id)
    {
        $path = getFilePath('pets');
        $uid = auth()->guard('user')->user()->id;
        foreach ($this->files as  $file) {
            try {
                $attachment = new PetAttachment();
                $attachment->pet_id = $id;
                $attachment->user_id = $uid;
                $attachment->attachment_type = 'previous_record';
                $attachment->attachment = fileUploader($file, $path);
                $attachment->save();
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Record could not upload'];
                return $notify;
            }
        }
        return 200;
    }

    public function getStateFromCountry(Request $request)
    {
        $states = States::where('country_id', $request->country)->orderBy('name','ASC')->get();
        return response()->json($states, 200);
    }

    public function petDoctorDetails($id)
    {
        $pageTitle   = "Pet Details";
        $userPet = UserPets::find($id);
        $imageattachments = $userPet->attachments()->where(['user_id' => $userPet->user_id, 'pet_id' => $userPet->id, 'attachment_type' => 'image'])->get();
        $videoattachments = $userPet->attachments()->where(['user_id' => $userPet->user_id, 'pet_id' => $userPet->id, 'attachment_type' => 'video'])->get();
        $previousrecordattachments = $userPet->attachments()->where(['user_id' => $userPet->user_id, 'pet_id' => $userPet->id, 'attachment_type' => 'previous_record'])->get();
        return view($this->activeTemplate . 'doctor_pet_details', compact('userPet', 'pageTitle','imageattachments','videoattachments','previousrecordattachments'));
    }

    //Google Login function
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    /**
     * Create a new controller instance.
     *
     * @return void
    */

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('google_id', $user->id)->first();
            if($finduser){
                //code for google login
                //end code google login
                $credentials = [
                    "access" => null,
                    "dr-option" => "2",
                    'username' => 'testuser100',
                    'password' => '123456dummy',
                    'email'    => $finduser->email,
                    //"captcha_secret" => "60a62df4e733b6e63e274ee368061f87f48907b99e436f3ff49dc28840252492",
                    //"captcha" => "164510",
                    //"_token" => "Il9dIBRP7qpco9vbMhPukA3MLf2hI4jg45MbNYtB",
                ];
               //dd($credentials);
               // $dm = new  \Illuminate\Http\Request($credentials);
                //dd($dm);
               // echo $pre_url= \Request::getRequestUri();
               // $param =request()->state;
               // if (!isset($param) && $param ==null){
                 //   $this->socailLogin($dm);

                //}
                //Auth::login($credentials);
                //dd(3);

                // Auth::attempt($credentials);
                //$this->login($finduser);
                return redirect('/login')->with(['username'=>'testuser100','password'=>'123456dummy','email'=>$finduser->email,"dr-option" => "2"]);
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                    'name' => $user->name,
                    'username' => 'testuser100',
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => Hash::make('123456dummy'),
                    'email_verified_at'=> date("Y-m-d H:i:s"),
                ]);
                // $credentials = [
                //     'username' => 'ishtiaq1000',
                //     'password' => '123456dummy',
                //     'email' => $newUser->email,
                //     "dr-option" => "2",
                // ];
                // $dm = new  \Illuminate\Http\Request($credentials);
                // $this->socailLogin($dm);
                //Auth::login($newUser);
                return redirect('/login')->with(['username'=>'testuser100','password'=>'123456dummy','email'=>$newUser->email,"dr-option" => "2"]);
            }

        } catch (Exception $e) {
            //dd($e->getMessage());
        }
    }

    //Facebook controller
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->first();
            if($finduser){
                Auth::login($finduser);
                return redirect('/');
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                    'name' => $user->name,
                    'facebook_id'=> $user->id,
                    'password' => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
                return redirect('/');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function smstest()
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "AC21cc5e49279947970f6459b57a3d6979";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $client = new \Twilio\Rest\Client($sid, $token);

        // Use the Client to make requests to the Twilio REST API
        $client->messages->create(
            // The number you'd like to send the message to
            '+923334847120',
            [
                // A Twilio phone number you purchased at https://console.twilio.com
                'from' => '+12024172477',
                // The body of the text message you'd like to send
                'body' => "Hey Jenny! Good luck on the bar exam!"
            ]
        );
    }

    public function voiceCall()
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "AC21cc5e49279947970f6459b57a3d6979";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $client = new \Twilio\Rest\Client($sid, $token);

        // Use the Client to make requests to the Twilio REST API
        $client->account->calls->create(  
            '+923334847120',
            '+12024172477',
            array(
                "url" => "http://demo.twilio.com/docs/voice.xml"
            )
        );
    }

    public function createRoomTw()
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "AC7f8d76cc819d8d8d1d1389fc86207d5f";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $client = new \Twilio\Rest\Client($sid, $token);

        $room = $client->video->v1->rooms->create(["uniqueName" => "DailyStandup"]);
        print($room->sid);
    }

    public function peartoPear()
    {
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "AC7f8d76cc819d8d8d1d1389fc86207d5f";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $client = new \Twilio\Rest\Client($sid, $token);
        $room = $client->video->v1->rooms->create(["statusCallback" => "http://example.org","type" => "peer-to-peer","uniqueName" => "SalesMeeting"]);
        print($room->sid);
    }

    public function recordRoom(){
        $sid = "AC7f8d76cc819d8d8d1d1389fc86207d5f";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $client = new \Twilio\Rest\Client($sid, $token);
        $room = $client->video->v1->rooms->create(["recordParticipantsOnConnect" => True,"statusCallback" => "http://example.org","type" => "group","uniqueName" => "DailyStandup"]);
        print($room->sid);
    }


    public function videoCall(){
        // Your Account SID and Auth Token from console.twilio.com
        $sid = "AC7f8d76cc819d8d8d1d1389fc86207d5f";
        $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
        $twilio  = new \Twilio\Rest\Client($sid, $token);
        // Use the Client to make requests to the Twilio REST API
        $room = $twilio->video->v1->rooms->create(["type" => "go","uniqueName" => "My First Video Room"]);
        print($room->sid);
    }

    // public function videotwilio(){
    //     $sid = "AC7f8d76cc819d8d8d1d1389fc86207d5f";
    //     $token = "ef1ed56e533d05d16e4d0abeb5eeaeea";
    //     $twilio = new \Twilio\Rest\Client($sid, $token);
    //     //$room sid RM6b8e1fa640b04e9ced66c1f62e00befa  
    //     // $room = $twilio->video->v1->rooms
    //     //                   ->create([
    //     //                                "type" => "go",
    //     //                                "uniqueName" => "SEARCHAVET1 Video Room"
    //     //                            ]
    //     //                   );
    //     //return $room;
    //     // print($room->sid);
    //     //    $playback_grant = $twilio->media->v1->playerStreamer("RM6b8e1fa640b04e9ced66c1f62e00befa")
    //     //                                 ->playbackGrant()
    //     //                                 ->create(["ttl" => 60]);

    //     //     print($playback_grant->sid);

    //     $player_streamer = $twilio->media->v1->playerStreamer->create("RM6b8e1fa640b04e9ced66c1f62e00befa");
    //     print($player_streamer->sid);
    //     dd($player_streamer);

    // }

    public function videotwilio()
    {
        // Required for all Twilio access tokens
        // To set up environmental variables, see http://twil.io/secure
        // $twilioAccountSid = getenv('TWILIO_ACCOUNT_SID');
        // $twilioApiKey = getenv('TWILIO_API_KEY');
        // $twilioApiSecret = getenv('TWILIO_API_KEY_SECRET');

        // // Required for Video grant
        // $roomName = 'cool room';
        // // An identifier for your app - can be anything you'd like
        // $identity = 'john_doe';

        // // Create access token, which we will serialize and send to the client
        // $token = new AccessToken(
        //     'AC7f8d76cc819d8d8d1d1389fc86207d5f',
        //     'SK6aa2388440a05894c44b695db90017f7',
        //     'pk47KpocthgY0ZmdfPiy8dhSCkWmQKQF',
        //     3600,
        //     $identity
        // );

        // // Create Video grant
        // $videoGrant = new VideoGrant();
        // $videoGrant->setRoom($roomName);

        // // Add grant to token
        // $token->addGrant($videoGrant);

        // // render token to string
        // echo $token->toJWT();

        // dd($token);
        $pageTitle = 'video';
        $rooms = [];
        try {
            $client = new \Twilio\Rest\Client($this->sid, $this->token);
            $allRooms = $client->video->v1->rooms->read([]);
            
             $rooms = array_map(function($room) {
                return $room->uniqueName;
             }, $allRooms);
     
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return view($this->activeTemplate . 'createroom', ['rooms' => $rooms,'pageTitle'=>$pageTitle]);
    }

    public function createRoom(Request $request)
    {
        $client = new \Twilio\Rest\Client($this->sid, $this->token);
        $exists = $client->video->v1->rooms->read([ 'uniqueName' => $request->roomName]);
        if (empty($exists)) {
                $client->video->rooms->create([
                'uniqueName' => $request->roomName,
                'type' => 'go',
                'recordParticipantsOnConnect' => false
            ]);
           // \Log::debug("created new room: ".$request->roomName);
        }
        return redirect('joinroom/'.$request->roomName);
    }

    public function joinRoom($roomName)
    {
        // A unique identifier for this user
        //$identity = Auth::guard('user')->user()->name;
        $pageTitle ='video';

        //\Log::debug("joined with identity: $identity");
        // $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, 'waliam');

        // $videoGrant = new VideoGrant();
        // $videoGrant->setRoom($roomName);

        // $token->addGrant($videoGrant);
        $twilioAccountSid = getenv('TWILIO_ACCOUNT_SID');
        $twilioApiKey = getenv('TWILIO_API_KEY');
        $twilioApiSecret = getenv('TWILIO_API_KEY_SECRET');

        // Required for Video grant
        //$roomName = 'cool room';
        // An identifier for your app - can be anything you'd like
        $identity = rand(3,1000);

        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(
            'ACfb3954ae0e36762176da089770d17df8',
            'SK90247f02c1def1b4c1b24eeb19d46a64',
            'Tsu7TTjHJJ4hhswL9g2loS7SfNY0XiHB',
            3600,
            $identity
        );

        // Create Video grant
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);
        // Add grant to token
        $token->addGrant($videoGrant);
        // render token to string
        //echo $token->toJWT();
        // dd($token);
        return view($this->activeTemplate . 'room', [ 'accessToken' => $token->toJWT(), 'roomName' => $roomName,'pageTitle'=>$pageTitle ]);
    }

    public function videoCallTwilio()
    {
        $pageTitle ='video call';
        return view($this->activeTemplate . 'videocall', ['pageTitle'=>$pageTitle ]);
    }
}
