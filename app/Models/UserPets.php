<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class UserPets extends Model
{
    use Searchable;

    protected $guarded = ['id'];
    protected $table = 'user_pets';

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function pettype(){
        return $this->belongsTo(PetType::class,'pet_type_id');
    }
    public function attachments(): HasMany
    {
        return $this->hasMany(PetAttachment::class, 'pet_id');
    }
    public function appointments(){
        return $this->hasMany(Appointment::class,'user_pet_id');
    }
}
