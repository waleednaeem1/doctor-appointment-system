<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class PetType extends Model
{
    
    use Searchable, GlobalStatus, HasApiTokens;

    protected $guarded = ['id'];
    protected $table = 'pet_type';

    public function doctors()
    {
        return $this->hasMany(Doctor::class,'pet_type_id');
    }

    // SCOPES

    public function scopeActive()
    {
        return $this->where('status', Status::ACTIVE);
    }

    public function scopeInactive()
    {
        return $this->where('status', Status::INACTIVE);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::ACTIVE) {
                $html = '<span class="badge badge--success">' . trans("Active") . '</span>';
            } elseif ($this->status == Status::INACTIVE) {
                $html = '<span class="badge badge--danger">' . trans("Inactive") . '</span>';
            }
            return $html;
        });
    }
}
