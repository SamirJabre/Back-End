<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusLocationController;
use App\Http\Controllers\BusSeatController;
use App\Http\Controllers\CoordinateController;
use App\Http\Controllers\DeactivatingAccount;
use App\Http\Controllers\DriverAppController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SendOtpAgain;
use App\Http\Controllers\TripBooking;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->middleware(middleware: 'checkverified');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});


Route::post('/sendotp', [SendOtpAgain::class, 'updateOtpAndSendEmail']);
Route::post('/validate-otp', [OtpController::class, 'validateOtp']);
Route::post('/delete-unverified', [DeactivatingAccount::class, 'deleteUnverifiedUsers']);


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/search', [SearchController::class, 'searchTrips']);
    Route::get('/trips', [SearchController::class, 'trips']);
    Route::post('/getuser', [SearchController::class, 'getUserById']);
    Route::post('/tripinfo', [SearchController::class, 'tripById']);
});


Route::post('/coordinates', [CoordinateController::class, 'getCoordinates']);
Route::post('/driver-reviews', [ReviewController::class, 'driverReviews']);
Route::post('/book-trip', [TripBooking::class, 'bookTrip']);
Route::post('/adminlogin', [AdminController::class, 'login']);
Route::get('/getusers', [AdminController::class, 'getAllUsers']);
Route::delete('/deleteuser/{id}', [AdminController::class, 'deleteUser']);
Route::get('/getcities', [AdminController::class, 'getCities']);
Route::post('/createtrip', [AdminController::class, 'createTrip']);
Route::post('/update-location', [BusLocationController::class, 'updateLocation']);
Route::post('/driver-app', [DriverAppController::class, 'createDriverApplication']);
Route::get('/get-apps', [AdminController::class, 'getAllApplications']);
Route::post('/approve-app', [AdminController::class, 'acceptApplicant']);
Route::post('/reject-app', [AdminController::class, 'rejectApplicant']);
Route::get('/buses', [AdminController::class, 'getBuses']);
Route::get('/drivers', [AdminController::class, 'getDrivers']);
Route::post('/assign', [AdminController::class, 'assignDriverToBus']);
Route::post('/driver-login', [DriverAppController::class, 'driverLogin']);
Route::post('/driver-trips', [DriverAppController::class, 'getTripsByDriverId']);
Route::post('/get-seats', [BusSeatController::class, 'getSeats']);
Route::post('/updateseat', [BusSeatController::class, 'updateSeat']);