<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\AppointmentManager;

class AppointmentController extends Controller
{
    use AppointmentManager;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->guard('user')->user();
            return $next($request);
        });
        $this->userType   = 'user';
        $this->userColumn = 'user_pets_id';
    }
}
