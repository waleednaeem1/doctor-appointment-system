<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listings;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Cities;
use App\Models\States;
use App\Models\Category;
class ListingsController extends Controller
{
    public function index()
    {
        $pageTitle = 'Listings';
        $allListings   = Listings::searchable(['item_title'])->orderBy('created_at', 'DESC')->paginate(getPaginate());
        return view('admin.listings.index', compact('pageTitle', 'allListings'));
    }
    public function editpage($id)
    {
        $pageTitle = 'Edit Listing';
        $listings    = Listings::findOrFail($id);
        $categories  = Category::all();
        $countries   = Country::all();
        $states      = States::all();
        $cities      = Cities::all();
        return view('admin.listings.edit', compact('listings','pageTitle','states','countries', 'cities', 'categories'));
    }
    public function store(Request $request, $id = 0)
    {
        if ($id) {
            $listing           = Listings::findOrFail($id);
            $notification       = 'Listings updated successfully';
        } else {
            $listing           = new Listings();
            $notification       = 'Listings added successfully';
        }
        $this->lisitingSave($listing, $request);
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    protected function lisitingSave($listing, $request)
    {
        if ($request->hasFile('item_image')) {
            try {
                $old = $listing->item_image;
                $listing->item_image = fileUploader($request->item_image, getFilePath('listing'), getFileSize('listing'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $general = gs();
        $listing->user_id = 1;
        $listing->item_title = $request->item_title;
        $listing->item_slug = $this->slugify($request->item_title);
        $listing->item_email = $request->item_email;
        $listing->item_phone = $request->item_phone;
        $listing->category_id = $request->category_id;
        $listing->country_id = $request->country;
        $listing->state_id = $request->state;
        $listing->city_id = $request->city;
        $listing->item_postal_code = $request->item_postal_code;
        $listing->item_address = $request->item_address;
        $listing->item_lat = $request->item_lat;
        $listing->item_lng = $request->item_lng;
        $listing->item_price = $request->item_price;
        $listing->item_website = $request->item_website;
        $listing->item_social_facebook = $request->item_social_facebook;
        $listing->item_social_twitter = $request->item_social_twitter;
        $listing->item_social_linkedin = $request->item_social_linkedin;
        $listing->item_social_whatsapp = $request->item_social_whatsapp;
        $listing->item_social_instagram = $request->item_social_instagram;
        $listing->item_description = $request->item_description;
        $listing->save();
    }
    
    public function form()
    {
        $pageTitle = 'Add New Listing';
        $categories  = Category::all();
        $countries   = Country::all();
        $states      = States::all();
        $cities      = Cities::all();
        return view('admin.listings.form', compact('pageTitle','states','countries', 'cities', 'categories'));
    }
}
