<?php

namespace App\Http\Controllers\Api;
use App\Constants\Status;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Frontend;
use App\Models\VetReviews;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class SiteController extends Controller
{
    public function blog()
    {
        $pageTitle = 'Blogs';
        $blogs = DB::table('frontends')
        ->select('id', 'data_keys', 'data_values','views', 'created_at', 'updated_at')
        ->where('data_keys', 'blog.element')
        ->latest()
        ->get();

        $strippedBlogs = [];

        foreach ($blogs as $post) {
            $dataValues = json_decode($post->data_values);
            if (isset($dataValues->description_nic)) {
                $description_nic = strip_tags($dataValues->description_nic);
            }
            $strippedBlogs[] = (object)[
                'id' => $post->id,
                'data_keys' => $post->data_keys,
                'has_image' => $dataValues->has_image,
                'title' => $dataValues->title,
                'slug' => slug($dataValues->title),
                'category' => $dataValues->category,
                'description_nic' => $description_nic,
                'blog_image' => $dataValues->blog_image,
                'views' => $post->views,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
        }
        $latestBlogs = DB::table('frontends')
        ->select('id', 'data_keys', 'data_values','views', 'created_at', 'updated_at')
        ->where('data_keys', 'blog.element')
        ->limit(10)
        ->get();

        $strippedLatestBlogs = [];

        foreach ($latestBlogs as $latestBlog) {
            $dataValues = json_decode($latestBlog->data_values);
            if (isset($dataValues->description_nic)) {
                $description_nic = strip_tags($dataValues->description_nic);
            }
            $strippedLatestBlogs[] = (object)[
                'id' => $latestBlog->id,
                'data_keys' => $latestBlog->data_keys,
                'has_image' => $dataValues->has_image,
                'title' => $dataValues->title,
                'category' => $dataValues->category,
                'description_nic' => $description_nic,
                'blog_image' => $dataValues->blog_image,
                'views' => $latestBlog->views,
                'created_at' => $latestBlog->created_at,
                'updated_at' => $latestBlog->updated_at,
            ];
        }
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->first();
        if($blogs->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Blogs not found', 'data' => []], 200);
        }
        $data = [
            'pageTitle' => $pageTitle,
            'blogs' => $strippedBlogs,
            'latest' => $strippedLatestBlogs,
            'sections' => $sections,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function blogDetails($slug, $id)
    {
        $pageTitle   = "Blog Details";
        $blog        = DB::table('frontends')
        ->select('id', 'data_keys', 'data_values','views', 'created_at', 'updated_at')
        ->where('id', $id)->where('data_keys', 'blog.element')->first();
        if (!$blog) {
            return response()->json(['success' => false, 'msg' => 'Blog not found', 'data' => []], 200);
        }
        $dataValuesBlog = json_decode($blog->data_values);
        if (isset($dataValuesBlog->description_nic)) {
            $description_nic_blog = strip_tags($dataValuesBlog->description_nic);
        }
        $blogings[] = (object)[
            'id' => $blog->id,
            'data_keys' => $blog->data_keys,
            'has_image' => $dataValuesBlog->has_image,
            'title' => $dataValuesBlog->title,
            'category' => $dataValuesBlog->category,
            'description_nic' => $description_nic_blog,
            'blog_image' => $dataValuesBlog->blog_image,
            'views' => $blog->views,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ];
        $blog = Frontend::find($id);
        $blog->views += 1;
        $blog->save();

        $posts = DB::table('frontends')
        ->select('id', 'data_keys', 'data_values','views', 'created_at', 'updated_at')
        ->latest()
        ->where('data_keys', 'blog.element')
        ->where('id', '!=', $id)
        ->limit(10)
        ->get();

        $strippedPosts = [];

        foreach ($posts as $post) {
            $dataValues = json_decode($post->data_values);
            if (isset($dataValues->description_nic)) {
                $description_nic = strip_tags($dataValues->description_nic);
            }
            $strippedPosts[] = (object)[
                'id' => $post->id,
                'data_keys' => $post->data_keys,
                'has_image' => $dataValues->has_image,
                'title' => $dataValues->title,
                'category' => $dataValues->category,
                'description_nic' => $description_nic,
                'blog_image' => $dataValues->blog_image,
                'views' => $post->views,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
            ];
        }

        $seoContents['keywords']           = $blog->meta_keywords ?? [];
        $seoContents['social_title']       = $blog->data_values->title;
        $seoContents['description']        = strLimit(strip_tags($blog->data_values->description_nic), 150);
        $seoContents['social_description'] = strLimit(strip_tags($blog->data_values->description_nic), 150);
        $seoContents['image']              = getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '830x460');
        $seoContents['image_size']         = '830x460';
        if(!$blog){
            return response()->json(['Success' => false, 'msg' => 'Blog not found'], 201);
        }
        $data = [
            'pageTitle' => $pageTitle,
            'blog' => $blogings,
            'latestPosts' => $strippedPosts,
            'seoContents' => $seoContents,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function faq()
    {
        $faqContent = getContent('faq.content', true);
        $faqElement = getContent('faq.element', null, false, true);
        $heading = __($faqContent->data_values->heading);
        $subHeading = __($faqContent->data_values->subheading);
        $data = [
            'heading' => $heading,
            'subHeading' => $subHeading,
            'faqElement' => [],
        ];
        foreach ($faqElement as $faq) {
            $faqData = [
                'question' => $faq->data_values->question,
                'answer' => $faq->data_values->answer,
            ];
            $data['faqElement'][] = $faqData;
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function privacy_policy()
    {
        $data['page_content'] = Frontend::where('data_keys', 'privacy_policy.content')->first();
        if(!isset($data['page_content']) || !isset($data['page_content']->data_values)){
            return response()->json(['success' => false, 'message' => 'Privacy Policy didnot add!']);
        }
        // $data['sections'] = Page::where('slug','privacy-policy')->first();
        // $pageTtile  = $data['page_content']->data_values->page_title;
        // $description  = $data['page_content']->data_values->description_nic;
        // $strippedContent = strip_tags($description);
        return response()->json(['success' =>true, 'data' => $data],200);
    }

    public function testimonial()
    {
        $testimonialContent = getContent('testimonial.content', true);
        $testimonialElement = getContent('testimonial.element', null, false, true);
        $heading = __($testimonialContent->data_values->heading);
        $subHeading = __($testimonialContent->data_values->subheading);
        $data = [
            'heading' => $heading,
            'subHeading' => $subHeading,
            'testimonialElement' => [],
        ];
        foreach ($testimonialElement as $testimonial) {
            $testimonialData = [
                'quote' => $testimonial->data_values->quote,
                'image' => @$testimonial->data_values->image,
                'name' => $testimonial->data_values->name,
                'designation' => $testimonial->data_values->designation,
            ];
            $data['testimonialElement'][] = $testimonialData;
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function department()
    {
        $departments = Department::orderBy('id', 'DESC')->get();
        if($departments->isEmpty()){
            return response()->json(['Success' => false, 'msg' => 'Departments not found', 'data' => []], 200);
        }
        $data = [
            'departments' => $departments,
        ];
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function contactSubmit(Request $request)
    {
        //$user_id = auth()->guard('user')->user()->id;
        $user_id = $request->user_id;
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);
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

        $data = $request->all();
        $checkEmail = User::userContact($data);

        return response()->json(['success' => true,'msg' => 'Ticket created successfully!', 'data' => $data,'ticket'=>$ticket->ticket], 200);

    }

    public function tickets($id)
    {
        $pageTitle      = "Tickets";
        $user_id = $id;
        $items = SupportTicket::where('user_id',$user_id)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return response()->json(['success' =>true, 'data' => $items  ],200);
    }

    public function viewTicket($id,$ticket)
    {
        $user_id        = $id;
        $user           =  User::where('id',$user_id)->get();
        $myTicket = SupportTicket::where('ticket', $ticket)->where('user_id',$user_id)->orderBy('id', 'desc')->firstOrFail();
        $messages = SupportMessage::where('support_ticket_id', $myTicket->id)->with('ticket', 'admin', 'attachments')->orderBy('id', 'desc')->get();
        return response()->json(['success' =>true, 'myTicket'=> $myTicket, 'messages'=>$messages,'user'=>$user],200);
    }

    public function feedbackSubmit(Request $request)
    {
        if($request->type == 'reaction'){
            $validator = Validator::make($request->all(), [
                'feedback'  => 'required',
                'experience'  => 'required',
                'user_id'  => 'required',
                'type'  => 'required',
            ]);
            if ($validator->fails()){
                return response()->json(['success' => false, 'error' => $validator->messages()], 201);
            }

            $user = $request->user_id;
            $feedback = new Feedback();
            $feedback->user_id = $user;
            $feedback->feedback = $request->feedback;
            $feedback->type = $request->type;
            $feedback->experience = $request->experience;
            $feedback->save();
            return response()->json(['success' => true, 'msg' => 'Feedback sent successfully'], 200);
        }elseif($request->type == 'report'){
            $validator = Validator::make($request->all(), [
                'feedback'  => 'required',
                'user_id'  => 'required',
                'type'  => 'required',
            ]);
            if ($validator->fails()){
                return response()->json(['success' => false, 'error' => $validator->messages()], 201);
            }
            $user = $request->user_id;
            $feedback = new Feedback();
            $feedback->user_id = $user;
            $feedback->feedback = $request->feedback;
            $feedback->type = $request->type;
            $feedback->save();

            return response()->json(['success' => true, 'msg' => 'Report sent successfully!'], 200);
        }else{
            return response()->json(['success' => false, 'error' => 'Please select correct type.'], 201);
        }
    }

    //Google Login
    public function handleProviderCallback(Request $request)
    {
        $validator = Validator::make($request->only('provider', 'access_provider_token'), [
            'provider' => ['required', 'string'],
            'access_provider_token' => ['required', 'string']
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);
        $provider = $request->provider;
        $validated = $this->validateProvider($provider);
        if (!is_null($validated))
            return $validated;
        $providerUser = Socialite::driver($provider)->userFromToken($request->access_provider_token);
        $getName =  explode(" ",$providerUser->getName());
        //dd($providerUser);
        $username   = strtolower($getName[0]).rand();
        $user_image = $providerUser->avatar ?? null;

        $user = User::updateOrCreate(['email' => $providerUser->getEmail()],
            [
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'user_image' => $user_image,
                'username' => $username,
            ]
        );
        $data =  [
            'token' => $user->createToken('Sanctom+Socialite')->plainTextToken,
            'user' => $user,
        ];
        return response()->json($data, 200);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google'])) {
            return response()->json(["message" => 'You can only login via google account'], 400);
        }
    }

    public function getVetReviews($id)
    {
        $vetReviews = VetReviews::with('user')->where(['doctor_id' => $id, 'status'=> 1])->orderBy('id', 'desc')->get();
        if(isset($vetReviews) && count($vetReviews) > 0){
            return response()->json(['success' => true, 'data' => $vetReviews, 'message' => 'Review found'], 200);
        }else{
            return response()->json(['success' => false, 'message' => 'No review found'], 201);
        }
    }

    public function reviewSubmit(Request $request)
    {
        $data = [
            'doctor_id' => $request->vetId,
            'user_id' => $request->userId,
            'review' => $request->vetReview,
            'rating' => $request->rating,
            'status' => 1,
        ];
        $vetReview = VetReviews::create($data);
        if(isset($vetReview)){
            return response()->json(['success' => true,'message' => 'Review added successfully'], 200);
        }else{
            return response()->json(['success' => false, 'message' => 'Review didnot add!']);
        }

    }
}
