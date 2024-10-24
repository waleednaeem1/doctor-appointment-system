<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetAttachment extends Model
{
    use HasFactory;
    protected $table = 'pet_attachments';
    protected $guarded = [];
}
