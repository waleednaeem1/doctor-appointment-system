<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Listings extends Model
{
    use Searchable;

    protected $guarded = ['id'];
    protected $table = 'items';

    protected $fillable = ['user_id','item_title','item_slug', 'item_email', 'item_phone','category_id','country_id','state_id','city_id','item_postal_code',
     'item_address', 'item_lat','item_lng','item_price','item_website','item_social_facebook','item_social_twitter','item_social_linkedin',
     'item_social_whatsapp', 'item_social_instagram', 'item_description','created_at','updated_at'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
