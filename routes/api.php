<?php

use App\Http\Controllers\Backend\CustomerDetailsController;
use App\Http\Controllers\Backend\LocationDetailsController;
use App\Http\Controllers\Backend\MemberController;
use App\Http\Controllers\Backend\MembershipController;
use App\Http\Controllers\Backend\RouteDetailsController;
use App\Http\Controllers\Backend\SeatsUpdateController;
use App\Http\Controllers\Backend\TripDetailsController;
use App\Http\Controllers\Backend\UsersData;
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
Route::post('/updateSeats',[SeatsUpdateController::class,'updateSeats'])->name('location.seats');
Route::resource('/routes',RouteDetailsController::class);
Route::resource('/locations',LocationDetailsController::class);
Route::resource('/trips',TripDetailsController::class);
Route::resource('/customer',CustomerDetailsController::class);
Route::post('/process-payment', [PaymentController::class,'processPayment']);
Route::resource('/membership',MembershipController::class);
Route::resource('/member',MemberController::class);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
