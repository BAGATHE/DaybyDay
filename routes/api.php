<?php

use App\Http\Controllers\api\AuthApiController;
use App\Http\Controllers\api\ChartApiController;
use App\Http\Controllers\api\DashboardApiController;
use App\Http\Controllers\api\ImportApiClientController;
use App\Http\Controllers\api\InvoiceApiController;
use App\Http\Controllers\api\OfferApiController;
use App\Http\Controllers\api\PaymentApiController;

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

Route::post('/login-api', [AuthApiController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboards', [DashboardApiController::class,'index']);
    Route::get('/offers', [OfferApiController::class,'index']);
    Route::get('/invoices', [InvoiceApiController::class,'index']);
    Route::get('/payments', [PaymentApiController::class,'index']);
    Route::get('/payements/sources',[ChartApiController::class,'getpayementBysource']);
    Route::post('/settings/update/remise',[\App\Http\Controllers\SettingsController::class,'updateRemise']);
    Route::get('/settings/remise',[\App\Http\Controllers\SettingsController::class,'getRemise']);
    Route::post('/payement/update',[PaymentApiController::class,'update']);
    Route::post('/payement/delete',[PaymentApiController::class,'delete']);
    Route::post('/import/client',[ImportApiClientController::class,'import']);

});


