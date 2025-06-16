<?php

use App\Http\Controllers\Api\AuthApiController as AuthController;
use App\Http\Controllers\Api\ContactApiController as ContactController;
use App\Http\Controllers\Api\RestaurantApiController as RestaurantController;
use App\Http\Controllers\Api\CronJobApiController as CronJobController;
use Illuminate\Support\Facades\Route;

// --------- Home Screen ------------- //

Route::get('/home-page/{latitude?}/{longitude?}', [RestaurantController::class, 'getAddress']);

// --------- App Users ------------- //
Route::post('user/{action}', [AuthController::class, 'index']);

// --------- Restaurant ------------- //
Route::post('restaurants', [RestaurantController::class, 'index']);
Route::post('restaurant/{action}', [RestaurantController::class, 'index']);

// --------- Contacts ------------- //
Route::post('/contacts', [ContactController::class, 'index']);

// --------- Cron Jobs ------------- //
Route::post('/cron-job/{action}', [CronJobController::class, 'index']);

Route::any('{url?}/{sub_url?}', function () {
    return response()->json([
        'code'    => '404',
        'status'  => false,
        'message' => 'Invalid Request',
            ], 404);
});
