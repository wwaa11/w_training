<?php

use App\Http\Controllers\WebController;
use App\Http\Middleware\adminAuth;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [WebController::class, 'test']);

Route::get('/login', [WebController::class, 'loginPage']);
Route::post('/login', [WebController::class, 'loginRequest']);
Route::post('/logout', [WebController::class, 'logoutRequest']);

Route::middleware([pr9Auth::class])->group(function () {

    Route::get('/changePassword', [WebController::class, 'IndexchangePassword']);
    Route::post('/changePassword', [WebController::class, 'changePassword']);
    Route::post('/updateReferance', [WebController::class, 'updateReferance']);
    Route::post('/updateSign', [WebController::class, 'updateSign']);

    Route::get('/', [WebController::class, 'index']);

    Route::get('/project/{id}', [WebController::class, 'ProjectIndex']);

    Route::post('/save', [WebController::class, 'TransactionSave']);
    Route::post('/delete', [WebController::class, 'TransactionDelete']);
    Route::post('/sign', [WebController::class, 'TransactionSign']);

    Route::get('/history', [WebController::class, 'history']);
});

Route::middleware([adminAuth::class])->group(function () {
    Route::get('/admin', [WebController::class, 'adminIndex']);

    Route::get('/admin/users', [WebController::class, 'adminUser']);
    Route::post('/admin/users/search', [WebController::class, 'adminUserSearch']);
    Route::post('/admin/resetpassword', [WebController::class, 'adminUserResetPassword']);

    Route::get('/admin/createProject', [WebController::class, 'adminCreateProject']);
    Route::post('/admin/createProject', [WebController::class, 'adminStoreProject']);
    Route::post('/admin/addDate', [WebController::class, 'adminCreateProject_AddDate']);

    Route::get('/admin/edit/{id}', [WebController::class, 'adminEditProject']);
    Route::post('/admin/update', [WebController::class, 'adminUpdateProject']);

    Route::get('/admin/project/{id}', [WebController::class, 'adminViewProject']);
    Route::post('/admin/project/createtransaction', [WebController::class, 'adminCreateTransaction']);

    Route::get('/admin/project/user/{id}', [WebController::class, 'adminProjectUser']);
    Route::post('/admin/project/user/delete', [WebController::class, 'adminProjectUserDelete']);

    Route::get('/admin/pdf/slot/{id}', [WebController::class, 'adminPDFSlot']);
    Route::get('/admin/excel/project/{id}', [WebController::class, 'adminExcelDate']);
    Route::get('/admin/excel/slot/{id}', [WebController::class, 'adminExcelSlot']);
    Route::get('/admin/onebook/project/{id}', [WebController::class, 'adminExcelOnebook']);
    Route::get('/admin/dbd/project/{id}', [WebController::class, 'adminExcelDBD']);

    Route::get('/admin/checkin/{id}', [WebController::class, 'admincheckinProject']);
    Route::get('/admin/approved/{id}', [WebController::class, 'adminapprovedProject']);
    Route::post('/admin/approveCheckin', [WebController::class, 'admincheckinProjectApprove']);
    Route::post('/admin/approveCheckinArray', [WebController::class, 'admincheckinProjectApproveArray']);

    Route::get('/admin/services', [WebController::class, 'dispatchServices']);
});
