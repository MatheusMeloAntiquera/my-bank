<?php

use Illuminate\Support\Facades\Route;
use App\Infra\Http\Controllers\EventController;
use App\Infra\Http\Controllers\ResetController;
use App\Infra\Http\Controllers\AccountController;

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

Route::controller(AccountController::class)->group(function () {
    Route::get('/balance', 'getBalance');
});

Route::controller(EventController::class)->group(function () {
    Route::post('/event', 'handleEvent');
});

Route::controller(ResetController::class)->group(function () {
    Route::post('/reset', 'reset');
});
