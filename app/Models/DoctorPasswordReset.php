<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorPasswordReset extends Model
{
    protected $table = "doctor_password_resets";
    protected $fillable = ['email', 'token', 'created_at'];
    protected $guarded = ['id'];
    public $timestamps = false;
}
