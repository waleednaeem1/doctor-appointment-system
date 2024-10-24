<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicDoctorTrack extends Model
{
    protected $guarded = ['id'];


    public function clinic()
    {
        return $this->belongsTo(Clinics::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

}
