<?php

use App\Http\Controllers\Backend\CustomerDetailsController;
use App\Http\Controllers\Backend\EmailManagingController;
use App\Http\Controllers\Backend\LocationDetailsController;
use App\Http\Controllers\Backend\MemberController;
use App\Http\Controllers\Backend\MembershipController;
use App\Http\Controllers\Backend\RouteDetailsController;
use App\Http\Controllers\Backend\SeatsUpdateController;
use App\Http\Controllers\Backend\TripDetailsController;
use App\Http\Controllers\Backend\TripMembersController;
use App\Http\Controllers\Backend\TripsDataCoontroller;
use App\Http\Controllers\Backend\UsersData;
use App\Http\Controllers\Frontend\CustomerMembershipController;
use App\Http\Controllers\Frontend\GetLoginCustomerDataController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/email/resend',[EmailManagingController::class,'resendMail'])->name('resend-mail');
Route::get('/emails',[EmailManagingController::class,'getEmails'])->name('get-emails');
Route::post('/updateSeats',[SeatsUpdateController::class,'updateSeats'])->name('location.seats');
Route::get('/trips/search',[TripsDataCoontroller::class,'search'])->name('search');
Route::get('/trips/all',[TripsDataCoontroller::class,'allTrips'])->name('allTrips');
Route::get('/trips/archieved',[TripsDataCoontroller::class,'archievedTrips'])->name('archievedTrips');
Route::get('/trips/active',[TripsDataCoontroller::class,'activeTrips'])->name('activeTrips');
Route::post('/savetrip', [TripMembersController::class, 'savetrip'])->name('trip-members');
Route::get('/gettrip', [TripMembersController::class, 'getTrips'])->name('get-trip-members');
Route::resource('/routes',RouteDetailsController::class);
Route::resource('/locations',LocationDetailsController::class);
Route::resource('/trips',TripDetailsController::class);
Route::resource('/customer',CustomerDetailsController::class);
Route::post('/process-payment', [PaymentController::class,'processPayment']);
Route::resource('/membership',MembershipController::class);
Route::resource('/member',MemberController::class);



// CUSTOMER APIS




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
