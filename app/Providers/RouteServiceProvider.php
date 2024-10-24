<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Laramin\Utility\VugiChugi;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::namespace($this->namespace)->middleware(VugiChugi::mdNm())->group(function(){

                Route::middleware(['web','maintenance'])
                    ->namespace('Gateway')
                    ->prefix('ipn')
                    ->name('ipn.')
                    ->group(base_path('routes/ipn.php'));

                Route::middleware(['web'])
                    ->namespace('Doctor')
                    ->prefix('veterinarian')
                    ->name('doctor.')
                    ->group(base_path('routes/doctor.php'));

                    Route::middleware(['web'])
                    ->namespace('Assistant')
                    ->prefix('assistant')
                    ->name('assistant.')
                    ->group(base_path('routes/assistant.php'));

                    Route::middleware(['web'])
                    ->namespace('Staff')
                    ->prefix('staff')
                    ->name('staff.')
                    ->group(base_path('routes/staff.php'));

                    Route::middleware(['web'])
                    ->namespace('User')
                    ->prefix('user')
                    ->name('user.')
                    ->group(base_path('routes/user.php'));

                    Route::middleware(['web'])
                    ->namespace('Admin')
                    ->prefix('admin')
                    ->name('admin.')
                    ->group(base_path('routes/admin.php'));

                // Route::middleware(['web','maintenance'])
                //     ->prefix('user')
                //     ->group(base_path('routes/user.php'));

                Route::middleware(['web','maintenance'])
                    ->group(base_path('routes/web.php'));

                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));
            });

        });

        Route::get('maintenance-mode','App\Http\Controllers\SiteController@maintenance')->name('maintenance');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
