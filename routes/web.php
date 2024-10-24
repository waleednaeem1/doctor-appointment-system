<?php

use App\Http\Controllers\Doctor\RegisterDoctorController;
use App\Http\Controllers\Specialist\RegisterSpecialistController;
use App\Http\Controllers\User\RegisterUserController;
use Illuminate\Support\Facades\Route;



Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');

Route::controller('SiteController')->group(function () {

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/about', 'about')->name('about');
    Route::get('/tickets', 'tickets')->name('tickets');
    Route::get('/terms_of_service', 'terms_of_service')->name('terms_of_service');
    Route::get('/privacy_policy', 'privacy_policy')->name('privacy_policy');
    Route::get('/feedback', 'feedback')->name('feedback');
    Route::post('/feedback', 'feedbackSubmit')->name('feedbackSubmit');
    Route::post('/report', 'reportSubmit')->name('reportSubmit');
    Route::get('/faqs', 'faqs')->name('faqs');


    Route::get('/myPets', 'myPets')->name('myPets');
    Route::post('/myPets', 'myPetSaved');
    Route::get('/knowledgeBase', 'knowledgeBase')->name('knowledgeBase');
    Route::get('/deleteSinglePetAttachment/{id}/{type}', 'deleteSinglePetAttachment');
    Route::get('/getPets/{id}', 'getPets')->name('getPets');
    Route::get('pet/{id}', 'petDetails')->name('pet.details');
    Route::post('petimages/{id}', 'petImages')->name('petimages');
    Route::post('petvideos/{id}', 'petVideos')->name('petvideos');
    Route::post('petrecords/{id}', 'petRecords')->name('petrecords');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::get('/getAppointmentsPets', 'getAppointmentsPets')->name('getAppointmentsPets');
    Route::get('/getAppointments', 'getAppointmentsHome')->name('getAppointmentsHome');
    Route::get('/getPetsDisease/{id}/{petid?}', 'getPetsDisease')->name('getpetsdisease');
    Route::get('/getPetsDiseases/{id}/{petid?}', 'getPetsDiseaseHome')->name('getpetsdiseases');
    Route::get('/moreVeterinarians', 'moreVeterinarians')->name('moreVeterinarians');
    /* Google Social Login */
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback');

    /*Face book login */
    Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    Route::get('auth/facebook/callback', 'handleFacebookCallback');

    //Twilio route
    //Route::get('videotwilio', 'videotwilio');
    Route::get('smstest', 'smstest');
    Route::get('voicecall', 'voiceCall');
    Route::get('videocall', 'videoCall');

    Route::get('createroomtw', 'createRoomTw');
    Route::get('peartopear', 'peartoPear');

    Route::get('recordroom', 'recordRoom');

    Route::get('video','videotwilio');
    Route::get('joinroom/{roomName}', 'joinRoom');
    Route::post('createroom', 'createRoom');
    Route::get('callgroup', 'videoCallTwilio');

    Route::get('login/{id?}', 'login')->name('login');
    Route::get('register', 'register')->name('register');
    Route::get('allvetsearch', 'allVeterianSearch')->name('allvetsearch');
    Route::get('pet/doctor/{id}', 'petDoctorDetails')->name('pet.doctor.details');

    Route::get('verify-account', function () {
        $pageTitle = 'Congratulations';
        return view('user.verifyaccount', compact( 'pageTitle'));
    })->name('verify-account');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
    Route::post('/country-states','getStateFromCountry');
});

Route::controller('DoctorAppointmentController')->prefix('veterinarians')->name('doctors.')->group(function () {
    Route::get('all', 'doctors')->name('all');
    Route::get('nearByVetsLocation', 'nearByVetsLocation')->name('nearByVetsLocation');
    Route::post('nearByVets', 'nearByVets')->name('nearByVets');
    Route::get('search', 'doctors')->name('search');
    Route::get('locations/{location}', 'locations')->name('locations');
    Route::get('states/{state}', 'states')->name('states');
    Route::get('departments/{department}', 'departments')->name('departments');
    Route::get('featured', 'featured')->name('featured');

    //Booking
    Route::get('booking/date/availability', 'availability')->name('appointment.available.date2');
    Route::post('appointment/store/{id}', 'store')->name('appointment.store');
    Route::get('booking/{id?}/{vetid?}', 'booking')->name('booking');
    Route::post('review/store/{id}', 'storeReviews')->name('review.store');
    Route::get('fees', 'feesCheck');

    Route::get('add-to-favorite/{doctorId}', 'addToFavorite')->name('add-to-favorite');
    Route::get('favorite-veterinarian-list', 'favoriteDoctorList')->name('favoriteDoctorList');
    Route::get('favorite-veterinarian-search', 'favoriteDoctorList')->name('favorite.search');
});

//Clinic
Route::controller('User\ClinicsController')->prefix('clinics')->name('clinics.')->group(function () {
    Route::get('all', 'clinics')->name('all');
    Route::get('details/{id}', 'details')->name('details');
    Route::get('search', 'clinics')->name('search');
    Route::get('doctorSearch', 'doctorSearch')->name('doctorSearch');
});

// Payment
Route::prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
    Route::any('/', 'deposit')->name('index');
    Route::post('insert', 'depositInsert')->name('insert');
    Route::post('insertpayment', 'depositPayment')->name('insertpayment');
    Route::get('confirm', 'depositConfirm')->name('confirm');
    Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
    Route::post('manual', 'manualDepositUpdate')->name('manual.update');
});

// User register
Route::post('register-user', [RegisterUserController::class, 'store'])->name('register-user');
Route::get('verify-email/{id}', [RegisterUserController::class, 'verifyMail'])->name('verify-email');


// Doctor register
Route::post('register-doctor', [RegisterDoctorController::class, 'store'])->name('register-doctor');
Route::get('doctor-verify-email/{id}', [RegisterDoctorController::class, 'verifyMail'])->name('doctor-verify-email');

// Specialist register
Route::post('register-specialist', [RegisterSpecialistController::class, 'store'])->name('register-specialist');
Route::get('specialist-verify-email/{id}', [RegisterSpecialistController::class, 'verifyMail'])->name('specialist-verify-email');
