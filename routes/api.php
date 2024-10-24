<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\DoctorAppointmentController;
use App\Http\Controllers\Api\ClinicsController;
use App\Http\Controllers\Api\SiteController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->group(function () {

});

Route::post('login', [LoginController::class, 'login']);
Route::post('reset-password-email', [ForgotPasswordController::class, 'sendResetCodeEmail']);
Route::post('verify-code', [ForgotPasswordController::class, 'verifyCode']);
Route::post('change-password', [ForgotPasswordController::class, 'changePassword']);
Route::get('edit-profile/{userId}', [UserController::class, 'editProfile']);
Route::post('update-profile', [UserController::class, 'profileUpdate']);
Route::post('password-update', [UserController::class, 'updatePassword']);


Route::get('home', [HomeController::class, 'index']);
Route::Post('/subscribe', [HomeController::class, 'subscribe']);

Route::post('register-user', [UserController::class, 'store']);
Route::post('verifyUsernameAndEmail', [UserController::class, 'checkUsernameAndEmail']);
Route::get('verify-email/{id}', [UserController::class, 'verifyMail']);
Route::get('blogs', [SiteController::class, 'blog']);
Route::get('blog/{slug}/{id}', [SiteController::class, 'blogDetails']);
Route::get('myPets', [PetController::class, 'myPets']);
Route::post('myPetsadd', [PetController::class, 'myPetSaved']);
Route::post('petPreviousRecord', [PetController::class, 'petPreviousRecord']);
Route::get('pet/{id}', [PetController::class, 'petDetails']);
Route::get('petTypes', [PetController::class, 'petType']);
Route::get('petdelete', [PetController::class, 'deletePet']);
Route::get('/getPetsDisease', [PetController::class, 'getPetsDisease']);
Route::get('/getPetsDiseaseDoctors', [PetController::class, 'getPetsDiseaseDoctors']);
Route::get('/getDiseaseofDoctorPet', [PetController::class, 'getDiseaseofDoctorPet']);
Route::get('faq', [SiteController::class, 'faq']);
Route::get('testimonial', [SiteController::class, 'testimonial']);
Route::get('departments', [SiteController::class, 'department']);
Route::get('department-doctors/{departmentId}', [DoctorAppointmentController::class, 'departmentDoctors']);
Route::post('/contact', [SiteController::class,'contactSubmit']);
Route::get('/tickets/{id}', [SiteController::class,'tickets']);
Route::get('/ticket/{id}/{ticket}', [SiteController::class,'viewTicket']);
Route::post('/feedback', [SiteController::class,'feedbackSubmit']);
Route::post('/googlelogin/callback', [SiteController::class, 'handleProviderCallback']);

Route::get('getVetReviews/{id}', [SiteController::class, 'getVetReviews']);
Route::post('/review/store', [SiteController::class,'reviewSubmit']);
Route::get('/privacy_policy', [SiteController::class, 'privacy_policy']);

Route::get('countries', [Controller::class, 'getAllCountries']);
Route::get('states/{country_id}', [Controller::class, 'getAllStates']);

Route::prefix('doctors')->group(function () {
    Route::get('all', [DoctorAppointmentController::class, 'doctors']);
    Route::get('search', [DoctorAppointmentController::class, 'search']);
    Route::get('search-doctor-by-state/{filter}', [DoctorAppointmentController::class, 'statesByDoctor']);
    Route::get('departments/{department}', [DoctorAppointmentController::class, 'departments']);
    Route::get('featured-doctors', [DoctorAppointmentController::class, 'featureDoctors']);


    //Booking
    Route::get('booking/{id?}', [DoctorAppointmentController::class, 'booking']);
    Route::get('booking/date/availability', [DoctorAppointmentController::class, 'availability']);
    Route::post('appointment/store/{id}', [DoctorAppointmentController::class, 'store']);

    Route::get('add-to-favorite/{doctorId}/{userId}',[DoctorAppointmentController::class, 'addToFavorite']);
    Route::get('favorite-doctor-list/{userId}',[DoctorAppointmentController::class, 'favoriteDoctorList']);
    Route::get('appointment-list/{userId}',[DoctorAppointmentController::class, 'appointmentList']);
});
Route::prefix('clinics')->group(function () {
    Route::get('all', [ClinicsController::class, 'clinics']);
    Route::get('details/{id}', [ClinicsController::class, 'details']);
});