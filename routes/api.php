<?php

use App\Http\Controllers\AuthController as AuthLogin;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PipelineController;
use App\Http\Middleware;

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

Route::post('login', [AuthLogin::class, 'login']);
Route::post('register', [AuthLogin::class, 'register']);
Route::get('login-google', [AuthLogin::class, 'redirectToGoogle']);
Route::get('login/google/callback', [AuthLogin::class, 'handleGoogleCallback']);

Route::middleware('jwt')->group(function () {
    Route::get('user', [AuthLogin::class, 'user'])->name('user');
    Route::post('logout', [AuthLogin::class, 'logout'])->name('logout');
});

Route::middleware('jwt')->group(function () {
    Route::resource('pipelines', PipelineController::class);
    Route::resource('cards', CardController::class);
    Route::put('cards/{card}/next', [CardController::class, 'nextStage']);
});


