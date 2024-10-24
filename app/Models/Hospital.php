<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use Searchable;

    // protected $guarded = ['id'];
    protected $table = 'hospitals';

}
