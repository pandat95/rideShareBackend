<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostRideOfferController;
use App\Http\Controllers\PostRideRequestController;
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


// Post Ride Offer
Route::middleware('auth:api')->post('/PostRideOffer', [PostRideOfferController::class, 'create']);
Route::middleware('auth:api')->post('/PostRideOffer/{id}/accept', [PostRideOfferController::class, 'accept']);

// Post Ride Request
Route::middleware('auth:api')->post('/PostRideRequest', [PostRideRequestController::class, 'create']);
Route::middleware('auth:api')->post('/PostRideRequest/{id}/accept', [PostRideRequestController::class, 'accept']);


Route::post('/register', [RegisterController::class,'register']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});