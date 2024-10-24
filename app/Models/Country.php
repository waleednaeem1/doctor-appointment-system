<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Searchable;
    protected $table = 'countries';

    protected $guarded = ['id'];

    public function state()
    {
        return $this->hasMany(States::class);
    }
}
