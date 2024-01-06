<?php

use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\MembershipHomeController;
use App\Http\Controllers\Backend\RoutesController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TripController;
use App\Http\Controllers\Backend\TripMembersController;
use App\Http\Controllers\Backend\TripViewController;
use App\Http\Controllers\Frontend\CustomerMembershipBuyController;
use App\Http\Controllers\Frontend\CustomerMembershipController;
use App\Http\Controllers\Frontend\CustomerTripsController;
use App\Http\Controllers\Frontend\GetActiveMembershipController;
use App\Http\Controllers\Frontend\GetCustomerMemberships;
use App\Http\Controllers\Frontend\GetCustomerProfile;
use App\Http\Controllers\Frontend\GetLoginCustomerTrips;
use App\Http\Controllers\Frontend\SaveTripCardController;
use App\Http\Controllers\Payment\newPaymentController as ControllersNewPaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// apis
Route::get('/customer-trips',[GetLoginCustomerTrips::class,'index'])->name('customer.trips');
Route::get('/customer-profile',[GetCustomerProfile::class,'index'])->name('customer-profile');
Route::post('/trip/pay-with-card',[SaveTripCardController::class,'index'])->name('trip.customer-card-payment');
Route::get('/customer-memberships',[GetCustomerMemberships::class,'index'])->name('customer-memberships');
Route::get('/active/membership',[GetActiveMembershipController::class,'index'])->name('active-membership');
Route::post('customer/membership-payment',[CustomerMembershipBuyController::class,'index'])->name('customer-membershipBuy');
Route::get('/', function () {
    return view('frontend.index');
});
Route::get('/customer/dashboard',function () {
    return view('CustomerSide.dashboard.index');
})->name('customer-dashboard');
Route::get('/customer/login',function () {
    return view('CustomerSide.login.login');
})->name('customer-login');
// SAVE TRIP
Route::post('/savetrip', [TripMembersController::class, 'savetrip'])->name('save-trip-members');
//
//setting route
Route::get('/setting',function () {
    return view('settings.index');
})->name('setting');
// set Gateway
Route::post('/update/gateway',[SettingController::class,'UpdateGateway'])->name('update-gateway')->middleware('admin');
Route::get('/get/gateway',[SettingController::class,'getGateway'])->name('get-gateway');


// here
Route::get('/membership/customer',CustomerMembershipController::class)->name('customer-membership');

Route::get('/trips/customer',CustomerTripsController::class)->name('customer-trip');


Route::group(['middleware'=>'admin'],function () {
    Route::get('/trip/{id}/view',TripViewController::class)->name('trip-view');
// membership home page
Route::get('/membership',MembershipHomeController::class)->name('membership');
// customer home page
Route::get('/customer',CustomerController::class)->name('customer');
//trip home page
Route::get('/trips',TripController::class)->name('trip');
// locarion home page
Route::get('/location',LocationController::class)->name('location');
// route home page
Route::get('/routes',RoutesController::class)->name('route');
// dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified',])->name('dashboard');

});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
