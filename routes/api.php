<?php

use App\Http\Controllers\HRController;
use Illuminate\Support\Facades\Route;

Route::post('/create-project', [HRController::class, 'api_createProject']);
Route::get('/get-transaction', [HRController::class, 'apt_getTransaction']);
Route::post('/approve-transaction', [HRController::class, 'api_approveTransaction']);
