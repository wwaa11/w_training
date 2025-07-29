<?php

use App\Http\Controllers\CoreController;
use App\Http\Controllers\HumanResourceControler;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\TrainingController;
use App\Http\Middleware\HrAdmin;
use App\Http\Middleware\NurseAdmin;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [CoreController::class, 'TEST_FUNCTION']);

Route::get('/services', [CoreController::class, 'DispatchServices']);

Route::get('/login', [CoreController::class, 'Login']);
Route::post('/login', [CoreController::class, 'LoginRequest']);
Route::post('/logout', [CoreController::class, 'LogoutRequest']);

Route::middleware([pr9Auth::class])->group(function () {
    // Base Function
    Route::post('/get/dateBetween', [CoreController::class, 'createProject_DeatBetween']);

    Route::get('/', [CoreController::class, 'Index']);
    Route::get('/profile', [CoreController::class, 'Profile']);
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
    Route::get('/nurse/history', [NurseController::class, 'History']);
    Route::get('/nurse/project/{id}', [NurseController::class, 'ProjectIndex']);
    Route::post('/nurse/project/create', [NurseController::class, 'TransactionCreate']);
    Route::post('/nurse/project/delete', [NurseController::class, 'TransactionDelete']);
    Route::post('/nurse/project/sign', [NurseController::class, 'TransactionSign']);

    // Training
    Route::get('/training', [TrainingController::class, 'Index']);
    Route::get('/training/history', [TrainingController::class, 'history']);
    Route::post('/training/getsession', [TrainingController::class, 'indexGetSessions'])->name('training.get.sessions');
    Route::post('/training/gettime', [TrainingController::class, 'indexgetTimes'])->name('training.get.times');
    Route::post('/training/register', [TrainingController::class, 'indexRegister'])->name('training.register');
    Route::post('/training/checkin', [TrainingController::class, 'indexCheckIn'])->name('training.checkin');
    Route::post('/training/change-registration', [TrainingController::class, 'changeRegistration'])->name('training.change.registration');
});

Route::middleware([HrAdmin::class])->group(function () {
    Route::get('/hr/function', [HumanResourceControler::class, 'addDatetoProject']);

    Route::get('/hr/admin/users', [CoreController::class, 'AllUserHR']);
    Route::post('/hr/admin/users/search', [CoreController::class, 'UserSearch']);
    Route::post('/hr/admin/resetpassword', [CoreController::class, 'UserResetPassword']);

    Route::get('/hr/admin', [HumanResourceControler::class, 'adminIndex']);
    Route::get('/hr/admin/createDEV', [HumanResourceControler::class, 'adminProjectCreate']);
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

    Route::get('/hr/admin/scores', [HumanResourceControler::class, 'adminScores']);
    Route::post('/hr/admin/importscores', [HumanResourceControler::class, 'ImportScore']);

    // Training
    // Dev routes
    Route::get('/training/admin/dev/delete', [TrainingController::class, 'deleteTestData']);
    Route::get('/training/admin/dev/seed', [TrainingController::class, 'seedData']);

    // Management routes
    Route::get('/training/admin', [TrainingController::class, 'adminIndex'])->name('training.admin.index');

    // Approve management routes
    Route::get('/training/admin/approve', [TrainingController::class, 'adminApprove'])->name('training.admin.approve.index');
    Route::post('/training/admin/approveUser', [TrainingController::class, 'adminApproveUser'])->name('training.admin.approve.user');
    Route::post('/training/admin/approveUsers', [TrainingController::class, 'adminApproveUsers'])->name('training.admin.approve.users');
    Route::post('/training/admin/TeacherapproveUsers', [TrainingController::class, 'adminApproveUsersTeacher'])->name('training.admin.approve.teacher');

    // Team management routes
    Route::get('/training/admin/teams', [TrainingController::class, 'adminTeamIndex'])->name('training.admin.teams.index');
    Route::get('/training/admin/teams/create', [TrainingController::class, 'adminTeamCreate'])->name('training.admin.teams.create');
    Route::post('/training/admin/teams', [TrainingController::class, 'adminTeamStore'])->name('training.admin.teams.store');
    Route::get('/training/admin/teams/{id}/edit', [TrainingController::class, 'adminTeamEdit'])->name('training.admin.teams.edit');
    Route::post('/training/admin/teams/{id}/update', [TrainingController::class, 'adminTeamUpdate'])->name('training.admin.teams.update');
    Route::post('/training/admin/teams/{id}/delete', [TrainingController::class, 'adminTeamDelete'])->name('training.admin.teams.delete');
    Route::get('/training/admin/teams/{id}/teachers', [TrainingController::class, 'adminTeamTeachers'])->name('training.admin.teams.teachers');

    // Teacher management routes
    Route::get('/training/admin/teachers/create', [TrainingController::class, 'adminTeacherCreate'])->name('training.admin.teachers.create');
    Route::post('/training/admin/teachers/store', [TrainingController::class, 'adminTeacherStore'])->name('training.admin.teachers.store');
    Route::get('/training/admin/teachers/{id}/edit', [TrainingController::class, 'adminTeacherEdit'])->name('training.admin.teachers.edit');
    Route::post('/training/admin/teachers/{id}/update', [TrainingController::class, 'adminTeacherUpdate'])->name('training.admin.teachers.update');
    Route::post('/training/admin/teachers/{id}/delete', [TrainingController::class, 'adminTeacherDelete'])->name('training.admin.teachers.delete');
    Route::get('/training/admin/teachers/{id}/sessions', [TrainingController::class, 'adminTeacherSessions'])->name('training.admin.teachers.sessions');

    // Session management routes
    Route::get('/training/admin/sessions/create', [TrainingController::class, 'adminSessionCreate'])->name('training.admin.sessions.create');
    Route::post('/training/admin/sessions/store', [TrainingController::class, 'adminSessionStore'])->name('training.admin.sessions.store');
    Route::get('/training/admin/sessions/{id}/edit', [TrainingController::class, 'adminSessionEdit'])->name('training.admin.sessions.edit');
    Route::post('/training/admin/sessions/{id}/update', [TrainingController::class, 'adminSessionUpdate'])->name('training.admin.sessions.update');
    Route::post('/training/admin/sessions/{id}/delete', [TrainingController::class, 'adminSessionDelete'])->name('training.admin.sessions.delete');

    // Time management routes
    Route::get('/training/admin/times/create', [TrainingController::class, 'adminTimeCreate'])->name('training.admin.times.create');
    Route::post('/training/admin/times/store', [TrainingController::class, 'adminTimeStore'])->name('training.admin.times.store');
    Route::get('/training/admin/times/{id}/edit', [TrainingController::class, 'adminTimeEdit'])->name('training.admin.times.edit');
    Route::post('/training/admin/times/{id}/update', [TrainingController::class, 'adminTimeUpdate'])->name('training.admin.times.update');
    Route::post('/training/admin/times/{id}/delete', [TrainingController::class, 'adminTimeDelete'])->name('training.admin.times.delete');

    // Date management routes
    Route::get('/training/admin/dates/{time_id}', [TrainingController::class, 'adminDatesIndex'])->name('training.admin.dates.index');
    Route::get('/training/admin/dates/{time_id}/create', [TrainingController::class, 'adminDateCreate'])->name('training.admin.dates.create');
    Route::post('/training/admin/dates/{time_id}/store', [TrainingController::class, 'adminDateStore'])->name('training.admin.dates.store');
    Route::get('/training/admin/dates/{id}/edit', [TrainingController::class, 'adminDateEdit'])->name('training.admin.dates.edit');
    Route::post('/training/admin/dates/{id}/update', [TrainingController::class, 'adminDateUpdate'])->name('training.admin.dates.update');
    Route::post('/training/admin/dates/{id}/delete', [TrainingController::class, 'adminDateDelete'])->name('training.admin.dates.delete');

    // User management routes
    Route::get('/training/admin/users', [TrainingController::class, 'adminUserIndex'])->name('training.admin.users.index');
    Route::post('/training/admin/users/import', [TrainingController::class, 'adminUserImport'])->name('training.admin.users.import');
    Route::get('/training/admin/users/create', [TrainingController::class, 'adminUserCreate'])->name('training.admin.users.create');
    Route::post('/training/admin/users/store', [TrainingController::class, 'adminUserStore'])->name('training.admin.users.store');
    Route::post('/training/admin/users/delete', [TrainingController::class, 'adminUserDelete'])->name('training.admin.users.destroy');

    // Register management routes
    Route::get('/training/admin/register', [TrainingController::class, 'adminRegisterIndex'])->name('training.admin.register.index');
    Route::post('/training/admin/register', [TrainingController::class, 'adminRegisterStore'])->name('training.admin.register.store');
    Route::post('/training/admin/unregister', [TrainingController::class, 'adminUnregisterUser'])->name('training.admin.unregister.user');

    // Export management routes
    Route::get('/training/admin/exports/index', [TrainingController::class, 'adminExportIndex'])->name('training.admin.exports.index');
    Route::get('/training/admin/exports/attends', [TrainingController::class, 'adminExportAttends'])->name('training.admin.exports.attends');
    Route::get('/training/admin/exports/hospitals', [TrainingController::class, 'adminExportHospitals'])->name('training.admin.exports.hospitals');
    Route::get('/training/admin/exports/onebooks', [TrainingController::class, 'adminExportOnebooks'])->name('training.admin.exports.onebooks');
});

Route::middleware([NurseAdmin::class])->group(function () {
    Route::get('/nurse/admin/users', [CoreController::class, 'AllUserNURSE']);
    Route::post('/nurse/admin/users/search', [CoreController::class, 'UserSearch']);
    Route::post('/nurse/admin/resetpassword', [CoreController::class, 'UserResetPassword']);

    Route::get('/nurse/admin', [NurseController::class, 'adminProjectIndex'])->name('NurseAdminIndex');
    Route::get('/nurse/admin/project/{project_id}', [NurseController::class, 'adminProjectManagement']);
    Route::post('/nurse/admin/deleteProject', [NurseController::class, 'adminProjectDelete']);

    Route::get('/nurse/admin/create', [NurseController::class, 'adminProjectCreate'])->name('NurseAdminCreate');
    Route::post('/nurse/admin/store', [NurseController::class, 'adminProjectStore'])->name('NurseAdminStore');

    Route::get('/nurse/admin/transactions/{project_id}', [NurseController::class, 'adminProjectTransaction']);
    Route::post('/nurse/admin/createTransaction', [NurseController::class, 'adminProjectCreateTransaction']);
    Route::post('/nurse/admin/deleteTransaction', [NurseController::class, 'adminProjectDeleteTransaction']);

    Route::get('/nurse/admin/approve', [NurseController::class, 'adminProjectApprove']);
    Route::post('/nurse/admin/approveUser', [NurseController::class, 'adminProjectApproveUser']);
    Route::post('/nurse/admin/approveUserArray', [NurseController::class, 'adminProjectApproveUserArray']);

    Route::post('/nurse/admin/addLecture', [NurseController::class, 'adminAddLecture']);
    Route::post('/nurse/admin/deleteLecture', [NurseController::class, 'adminDeleteLecture']);
    Route::post('/nurse/lecturer/update-score', [NurseController::class, 'updateLecturerScore'])->name('nurse.lecturer.update-score');

    Route::get('/nurse/admin/export/excel/users/{project_id}', [NurseController::class, 'ExcelUserExport']);
    Route::get('/nurse/admin/export/excel/lectures/{project_id}', [NurseController::class, 'ExcelLectureExport']);
    Route::get('/nurse/admin/export/excel/datelecture/{date_id}', [NurseController::class, 'ExcelDateLectureExport']);
    Route::get('/nurse/admin/export/excel/dbd/{project_id}', [NurseController::class, 'ExcelDBDExport']);
    Route::get('/nurse/admin/export/excel/type/{project_id}', [NurseController::class, 'ExcelTypeExport']);
    Route::get('/nurse/admin/export/excel/date/users/{date_id}', [NurseController::class, 'ExcelDateUserExport']);
    Route::get('/nurse/admin/export/excel/date/dbd/{date_id}', [NurseController::class, 'ExcelDateDBDExport']);
    Route::get('/nurse/admin/export/excel/onebook/{date_id}', [NurseController::class, 'ExcelOneBookExport']);

    Route::get('/nurse/admin/userscore', [NurseController::class, 'UserScore']);
    Route::get('/nurse/admin/userscoreexport/{department}', [NurseController::class, 'UserScoreExport']);

});
