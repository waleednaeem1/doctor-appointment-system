<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscription;
use App\Mail\UserContact;

class User extends Authenticatable
{
    use Searchable, HasApiTokens;

    public $table = 'users';
    protected $fillable = ['name','email', 'postal_code', 'phone','address', 'country_id', 'state_id', 'city_id','latitude','longitude','user_image', 'status', 'gender','google_id','facebook_id','username','password','email_verified_at','created_at','updated_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public static function subscription($data)
    {
        Mail::send(new Subscription($data));
        return true;
    }
    public static function userContact($data)
    {
        Mail::send(new UserContact($data));
        return true;
    }
    function haversine($lat1, $lon1, $lat2, $lon2) {
        $rad = M_PI / 180;
        $lat1 *= $rad;
        $lon1 *= $rad;
        $lat2 *= $rad;
        $lon2 *= $rad;
        $lonDelta = $lon2 - $lon1;
        $a = pow(cos($lat2) * sin($lonDelta), 2) +
             pow(cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($lonDelta), 2);
        $b = sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lonDelta);
        $angle = atan2(sqrt($a), $b);
        return $angle * 6371; // Radius of the Earth in kilometers
    }
    
    
}
