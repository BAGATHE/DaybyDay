<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\DashboardApiController;
use Illuminate\Http\Request;

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
});