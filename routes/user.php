<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->controller('LoginController')->group(function () {
    Route::get('/', 'showLoginForm')->name('login');
    Route::post('/', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout');
    

    Route::controller('ForgotPasswordController')->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'sendResetCodeEmail');
        Route::get('password/code-verify', 'codeVerify')->name('password.code.verify');
        Route::post('password/verify-code', 'verifyCode')->name('password.verify.code');
        Route::get('email/resend/{email}', 'resendEmail')->name('email.resend');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('user')->group(function () {
    Route::controller('UserController')->group(function(){
        Route::get('userhome', 'home')->name('home');
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('mypets', 'myPets')->name('mypets');
        Route::post('mypets', 'myPetSaved');
        Route::get('petslist', 'myAllPets')->name('petslist');
        Route::get('petdelete/{id}', 'deletePet');
        Route::get('petdetail/{id}', 'petdetail')->name('petdetail');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

    });


    Route::controller('UserController')->prefix('information')->name('info.')->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');

    });

    //Appointments
    Route::controller('AppointmentController')->prefix('appointment')->name('appointment.')->group(function () {
        Route::get('booking', 'details')->name('booking');
        Route::get('booking/availability/date', 'availability')->name('available.date');
        Route::post('store/{id}', 'store')->name('store');

        //Appointment
        Route::get('new', 'petappoint')->name('new');
        Route::post('dealing/{id}', 'done')->name('dealing');

        Route::get('service/done', 'userDoneService')->name('done');

        Route::post('remove/{id}', 'remove')->name('remove');
        Route::get('trashed', 'userServiceTrashed')->name('trashed');
    });




});

