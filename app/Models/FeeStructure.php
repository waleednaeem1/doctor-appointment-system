<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'fees_structure';
    protected $fillable = ['doctor_id','start_time','end_time','fees','created_at'];
}
