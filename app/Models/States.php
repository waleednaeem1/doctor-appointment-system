<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use Searchable;
    protected $table = 'states';
    protected $guarded = ['id'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class,'state_id');
    }
    public function clinics()
    {
        return $this->hasMany(Clinics::class,'state');
    }

}
