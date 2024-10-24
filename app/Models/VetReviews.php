<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class VetReviews extends Model
{
    use Searchable;
    protected $table = 'vet_reviews';
    protected $fillable = ['doctor_id', 'user_id','review','rating','status', 'created_at'];

    public function doctor(){
    	return $this->belongsTo(Doctor::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function assistant(){
    	return $this->belongsTo(Assistant::class);
    }

    public function staff(){
    	return $this->belongsTo(Staff::class);
    }
}
