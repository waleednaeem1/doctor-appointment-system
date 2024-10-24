<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordReset extends Model
{
    protected $table = "user_password_resets";
    protected $fillable = ['email','status','token','created_at'];
    protected $guarded = ['id'];
    public $timestamps = false;
}
