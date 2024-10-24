<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Clinics extends Model
{
    use Searchable;

    protected $guarded = ['id'];
    protected $table = 'clinics';

    // public function doctors()
    // {
    //     return $this->hasMany(Doctor::class);
    // }
    public function doctors(){
        return $this->belongsToMany(Doctor::class,'clinic_doctor_tracks')->with('department', 'location')->withTimestamps();
    }

    public function staffs(){
        return $this->belongsToMany(Staff::class,'clinic_staff_tracks')->withTimestamps();
    }
    public function state()
    {
        return $this->belongsTo(States::class, 'id');
    }
    public function futuredoctors(){
        return $this->belongsToMany(Doctor::class,'clinic_doctor_tracks');
    }
    public function clinicDoctors(){
        return $this->belongsToMany(Doctor::class,'clinic_doctor_tracks');
    }

}
