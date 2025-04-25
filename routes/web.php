<?php

use App\Http\Controllers\CoreController;
use App\Http\Controllers\HumanResourceControler;
use App\Http\Controllers\WebController;
use App\Http\Middleware\adminAuth;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [CoreController::class, 'TEST_FUNCTION']);

Route::get('/login', [CoreController::class, 'Login']);
Route::post('/login', [CoreController::class, 'LoginRequest']);
Route::post('/logout', [CoreController::class, 'LogoutRequest']);

Route::middleware([pr9Auth::class])->group(function () {

    Route::get('/', [CoreController::class, 'Index']);
    Route::get('/profile', [CoreController::class, 'IndexchangePassword']);
    Route::get('/profile/changePassword', [CoreController::class, 'Profile']);
    Route::post('/profile/changePassword', [CoreController::class, 'UpdateProfile']);
    Route::post('/profile/updateReferance', [CoreController::class, 'UpdateReferance']);
    Route::post('/profile/updateSign', [CoreController::class, 'UpdateSign']);
    Route::post('/profile/updateGender', [CoreController::class, 'UpdateGender']);

    // Human Resources
    Route::get('/hr/main', [HumanResourceControler::class, 'Index']);
    Route::get('/hr/history', [HumanResourceControler::class, 'History']);

    Route::get('/hr/project/{id}', [HumanResourceControler::class, 'ProjectIndex']);
    Route::post('/hr/project/create', [HumanResourceControler::class, 'TransactionCreate']);
    Route::post('/hr/project/delete', [HumanResourceControler::class, 'TransactionDelete']);
    Route::post('/hr/project/sign', [HumanResourceControler::class, 'TransactionSign']);

});

Route::middleware([adminAuth::class])->group(function () {

    Route::get('/hr/admin', [HumanResourceControler::class, 'adminIndex']);
    Route::get('/hr/admin/project/{id}', [HumanResourceControler::class, 'adminProjectManagement']);

    Route::get('/admin/users', [WebController::class, 'adminUser']);
    Route::post('/admin/users/search', [WebController::class, 'adminUserSearch']);
    Route::post('/admin/resetpassword', [WebController::class, 'adminUserResetPassword']);

    Route::get('/admin/createProject', [WebController::class, 'adminCreateProject']);
    Route::post('/admin/createProject', [WebController::class, 'adminStoreProject']);
    Route::post('/admin/addDate', [WebController::class, 'adminCreateProject_AddDate']);

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
