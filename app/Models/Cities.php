<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use Searchable;
    protected $table = 'cities';
    protected $guarded = ['id'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class,'city_id');
    }
}
