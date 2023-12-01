<?php

use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\MembershipHomeController;
use App\Http\Controllers\Backend\RoutesController;
use App\Http\Controllers\Backend\TripController;
use App\Http\Controllers\Backend\TripViewController;
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




Route::get('/', function () {
    return view('frontend.index');
});
//
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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
