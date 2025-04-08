<?php

use App\Http\Controllers\WebController;
use App\Http\Middleware\adminAuth;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [WebController::class, 'intiAppData']);

Route::get('/login', [WebController::class, 'loginPage']);
Route::post('/login', [WebController::class, 'loginRequest']);
Route::post('/logout', [WebController::class, 'logoutRequest']);

Route::middleware([pr9Auth::class])->group(function () {
    Route::get('/changePassword', [WebController::class, 'IndexchangePassword']);
    Route::post('/changePassword', [WebController::class, 'changePassword']);

    Route::get('/', [WebController::class, 'index']);
    Route::get('/history', [WebController::class, 'history']);

    Route::get('/project/{id}', [WebController::class, 'ProjectIndex']);
    Route::post('/save', [WebController::class, 'TransactionSave']);
    Route::post('/delete', [WebController::class, 'TransactionDelete']);
    Route::post('/sign', [WebController::class, 'TransactionSign']);

});

Route::middleware([adminAuth::class])->group(function () {
    Route::get('/admin', [WebController::class, 'adminIndex']);

    Route::get('/admin/users', [WebController::class, 'adminUser']);
    Route::post('/admin/resetpassword', [WebController::class, 'adminUserResetPassword']);

    Route::get('/admin/createProject', [WebController::class, 'adminCreateProject']);
    Route::post('/admin/createProject', [WebController::class, 'adminStoreProject']);
    Route::post('/admin/addDate', [WebController::class, 'adminCreateProject_AddDate']);

    Route::get('/admin/excel/{id}', [WebController::class, 'Project_allTransactions']);

    Route::get('/admin/project/{id}', [WebController::class, 'adminViewProject']);
    Route::get('/admin/exceldate/{id}', [WebController::class, 'adminExcelProjectDate']);

    Route::get('/admin/checkin/{id}', [WebController::class, 'admincheckinProject']);
    Route::get('/admin/approved/{id}', [WebController::class, 'adminapprovedProject']);
    Route::post('/admin/approveCheckin', [WebController::class, 'admincheckinProjectApprove']);

});
