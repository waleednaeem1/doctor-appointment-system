<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddDoctor;

class Doctor extends Authenticatable
{
    use Searchable, GlobalStatus, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'ver_code_send_at'  => 'datetime',
        'serial_or_slot'    => 'object',
        'speciality'        => 'object',
    ];

    public function assistantDoctorTrack()
    {
        return $this->hasMany(AssistantDoctorTrack::class);
    }

    public function clinicDoctorTrack()
    {
        return $this->hasMany(ClinicDoctorTrack::class);
    }
    public function assistants()
    {
        return $this->hasMany(Assistant::class);
    }

    public function clinics()
    {
        return $this->hasMany(Clinics::class);
    }
    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function clinicsViseDepartment()
    {
        return $this->belongsTo(Department::class, 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function state()
    {
        return $this->belongsTo(States::class);
    }
    public function petType()
    {
        return $this->belongsTo(PetType::class);
    }
    
    public function favorite()
    {
        return $this->belongsTo(Favorite::class, 'id', 'doctor_id');
    }
    
    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function educationDetails()
    {
        return $this->hasMany(Education::class);
    }

    public function reviews()
    {
        return $this->hasMany(VetReviews::class);
    }

    public function experienceDetails()
    {
        return $this->hasMany(Experience::class);
    }

    public function socialIcons()
    {
        return $this->hasMany(SocialIcon::class);
    }

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE);
    }
    public function scopeInactive($query)
    {
        return$query->where('status', Status::INACTIVE);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::ACTIVE) {
                $html = '<span class="badge badge--success">' . trans("Active") . '</span>';
            } elseif ($this->status == Status::INACTIVE) {
                $html = '<span class="badge badge--danger">' . trans("Inactive") . '</span>';
            }
            return $html;
        });
    }

    
    public function verifiedBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->email_verified_at != null) {
                $html = '<span class="badge badge--success">' . trans("Verified") . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . trans("Not Verified") . '</span>';
            }
            return $html;
        });
    }


    public function featureBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->featured == Status::YES) {
                $html = '<span class="badge badge--success">' . trans("Featured") . '</span>';
            } elseif ($this->featured == Status::NO) {
                $html = '<span class="badge badge--warning">' . trans("Non Featured") . '</span>';
            }
            return $html;
        });
    }

    public static function newDoctor($data, $password)
    {
        Mail::send(new AddDoctor($data, $password));
        return true;
    }
}
