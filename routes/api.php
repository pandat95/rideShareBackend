<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PostRideOfferController;
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


Route::post('/register', [RegisterController::class,'register']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
