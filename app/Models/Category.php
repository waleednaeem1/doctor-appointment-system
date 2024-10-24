<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Searchable;

    protected $guarded = ['id'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
