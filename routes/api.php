<?php

use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerScheduleController;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'api'], function()
{
    
    Route::post('employer', [EmployerController::class, 'store']);
    Route::post('employer/{id}/schedule', [EmployerScheduleController::class, 'store']);
});