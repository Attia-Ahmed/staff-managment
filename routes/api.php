<?php

use App\Http\Controllers\EmployerAnalyticsController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployerStatusController;

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

Route::group(['namespace' => 'api'], function () {

    Route::post('employer', [EmployerController::class, 'store']);
    Route::post('employer/{employer}/schedule', [EmployerScheduleController::class, 'store']);
    Route::post('employer/{employer}/status', [EmployerController::class, 'changeStatus']);
    Route::get('employer/{employer}/analytics', [EmployerAnalyticsController::class, 'show']);
});
