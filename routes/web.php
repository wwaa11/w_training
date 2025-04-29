<?php

use App\Http\Controllers\CoreController;
use App\Http\Controllers\HumanResourceControler;
use App\Http\Controllers\NurseController;
use App\Http\Middleware\HrAdmin;
use App\Http\Middleware\NurseAdmin;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [CoreController::class, 'TEST_FUNCTION']);
Route::get('/delete', [CoreController::class, 'delete']);
Route::get('/services', [CoreController::class, 'DispatchServices']);

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
    Route::get('/hr', [HumanResourceControler::class, 'Index']);
    Route::get('/hr/history', [HumanResourceControler::class, 'History']);
    Route::get('/hr/project/{id}', [HumanResourceControler::class, 'ProjectIndex']);
    Route::post('/hr/project/create', [HumanResourceControler::class, 'TransactionCreate']);
    Route::post('/hr/project/delete', [HumanResourceControler::class, 'TransactionDelete']);
    Route::post('/hr/project/sign', [HumanResourceControler::class, 'TransactionSign']);

    // Nurse
    Route::get('/nurse', [NurseController::class, 'Index']);
    Route::get('/nurse/project/{id}', [NurseController::class, 'ProjectIndex']);
    Route::post('/nurse/project/create', [NurseController::class, 'TransactionCreate']);
    Route::post('/nurse/project/delete', [NurseController::class, 'TransactionDelete']);
    Route::post('/nurse/project/sign', [NurseController::class, 'TransactionSign']);
});

Route::middleware([HrAdmin::class])->group(function () {
    Route::get('/dev/services', [CoreController::class, 'DispatchServices']);

    Route::get('/admin/users', [CoreController::class, 'AllUser']);
    Route::post('/admin/users/search', [CoreController::class, 'UserSearch']);
    Route::post('/admin/resetpassword', [CoreController::class, 'UserResetPassword']);

    // Human Resources
    Route::get('/hr/admin', [HumanResourceControler::class, 'adminIndex']);
    Route::get('/hr/admin/project/{id}', [HumanResourceControler::class, 'adminProjectManagement']);

    Route::get('/hr/admin/link/{id}', [HumanResourceControler::class, 'adminProjectLink']);
    Route::post('/hr/admin/link/update', [HumanResourceControler::class, 'adminProjectLinkUpdate']);

    Route::get('/hr/admin/transactions/{id}', [HumanResourceControler::class, 'adminProjectTransactions']);
    Route::post('/hr/admin/createTransaction', [HumanResourceControler::class, 'adminProjectCreateTransaction']);
    Route::post('/hr/admin/deleteTransaction', [HumanResourceControler::class, 'adminProjectDeleteTransaction']);

    Route::get('/hr/admin/approve', [HumanResourceControler::class, 'adminProjectApprove']);
    Route::post('/hr/admin/approveUser', [HumanResourceControler::class, 'adminProjectApproveUser']);
    Route::post('/hr/admin/approveUserArray', [HumanResourceControler::class, 'adminProjectApproveUserArray']);

    Route::get('/hr/admin/export/pdf/time/{item_id}', [HumanResourceControler::class, 'PDFTimeExport']);
    Route::get('/hr/admin/export/excel/date/{slot_id}', [HumanResourceControler::class, 'ExcelDateExport']);
    Route::get('/hr/admin/export/excel/all_date/{project_id}', [HumanResourceControler::class, 'ExcelAllDateExport']);
    Route::get('/hr/admin/export/excel/onebook/{project_id}', [HumanResourceControler::class, 'ExcelOneBookExport']);
    Route::get('/hr/admin/export/excel/dbd/{project_id}', [HumanResourceControler::class, 'ExcelDBDExport']);

});

Route::middleware([NurseAdmin::class])->group(function () {
    // Nurse
    Route::get('/nurse/admin', [NurseController::class, 'adminProjectIndex'])->name('NurseAdminIndex');
    Route::get('/nurse/admin/project/{project_id}', [NurseController::class, 'adminProjectManagement']);

    Route::get('/nurse/admin/create', [NurseController::class, 'adminProjectCreate'])->name('NurseAdminCreate');
    Route::post('/nurse/admin/store', [NurseController::class, 'adminProjectStore'])->name('NurseAdminStore');

    Route::get('/nurse/admin/transactions/{project_id}', [NurseController::class, 'adminProjectTransaction']);
    Route::post('/nurse/admin/createTransaction', [NurseController::class, 'adminProjectCreateTransaction']);
    Route::post('/nurse/admin/deleteTransaction', [NurseController::class, 'adminProjectDeleteTransaction']);

    Route::get('/nurse/admin/approve', [NurseController::class, 'adminProjectApprove']);
    Route::post('/nurse/admin/approveUser', [NurseController::class, 'adminProjectApproveUser']);
    Route::post('/nurse/admin/approveUserArray', [NurseController::class, 'adminProjectApproveUserArray']);

});
