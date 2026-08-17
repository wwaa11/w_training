<?php

use App\Http\Controllers\HRController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Training / HRD API Routes
|--------------------------------------------------------------------------
| All endpoints require Authorization: Bearer {Token_API}
*/

Route::post('/create-project', [HRController::class, 'api_createProject']);
Route::post('/cancel-project', [HRController::class, 'api_cancelProject']);
Route::get('/get-transaction', [HRController::class, 'apt_getTransaction']);
Route::post('/approve-transactions', [HRController::class, 'api_approveTransactions']);

Route::get('/project-detail', [HRController::class, 'api_getProjectDetail']);

Route::post('/date/add', [HRController::class, 'api_addDate']);
Route::post('/date/edit', [HRController::class, 'api_editDate']);
Route::post('/date/remove', [HRController::class, 'api_removeDate']);

Route::post('/time/add', [HRController::class, 'api_addTime']);
Route::post('/time/edit', [HRController::class, 'api_editTime']);
Route::post('/time/remove', [HRController::class, 'api_removeTime']);

Route::post('/participant/add', [HRController::class, 'api_addParticipant']);
Route::post('/participant/remove', [HRController::class, 'api_removeParticipant']);

Route::post('/lecturer/add', [HRController::class, 'api_addLecturer']);
Route::post('/lecturer/remove', [HRController::class, 'api_removeLecturer']);
