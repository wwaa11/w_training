<?php

use App\Http\Controllers\CoreController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\HumanResourceControler;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\TrainingController;
use App\Http\Middleware\HrAdmin;
use App\Http\Middleware\NurseAdmin;
use App\Http\Middleware\pr9Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TrainingController::class, 'test']);

// Authentication Routes
Route::get('/login', [CoreController::class, 'Login'])->name('login');
Route::post('/login', [CoreController::class, 'LoginRequest'])->name('login.post');
Route::post('/logout', [CoreController::class, 'LogoutRequest'])->name('logout');
Route::get('/session/check', [CoreController::class, 'checkSession'])->name('session.check');

// ============================================================================
// AUTHENTICATED USER ROUTES
// ============================================================================

Route::middleware([pr9Auth::class])->group(function () {

    // ========================================================================
    // CORE USER ROUTES
    // ========================================================================

    Route::get('/', [CoreController::class, 'Index'])->name('index');
    Route::get('/profile', [CoreController::class, 'Profile'])->name('profile.index');
    Route::post('/profile/changePassword', [CoreController::class, 'UpdateProfile'])->name('profile.changePassword');
    Route::post('/profile/updateReferance', [CoreController::class, 'UpdateReferance'])->name('profile.updateReferance');
    Route::post('/profile/updateSign', [CoreController::class, 'UpdateSign'])->name('profile.updateSign');
    Route::post('/profile/updateGender', [CoreController::class, 'UpdateGender'])->name('profile.updateGender');

    // ========================================================================
    // HRD (Human Resource Development) - User Routes
    // ========================================================================

    Route::prefix('hrd')->name('hrd.')->group(function () {
        Route::get('/', [HRController::class, 'Index'])->name('index');
        Route::get('/history', [HRController::class, 'userHistory'])->name('history');
        Route::get('/user-guide', [HRController::class, 'userGuide'])->name('user-guide');

        // Project Routes
        Route::prefix('projects/{id}')->name('projects.')->group(function () {
            Route::get('/', [HRController::class, 'projectShow'])->name('show');
            Route::post('/register', [HRController::class, 'projectRegisterStore'])->name('register.store');
            Route::post('/attend', [HRController::class, 'projectAttendStore'])->name('attend.store');
            Route::post('/stamp/{attendId}', [HRController::class, 'projectStampAttendance'])->name('stamp.store');
            Route::delete('/reselect', [HRController::class, 'projectReselectRegistration'])->name('reselect');
            Route::delete('/unregister/{registrationId}', [HRController::class, 'projectUnregister'])->name('unregister');
        });
    });

    // ========================================================================
    // HR (Human Resource) - User Routes
    // ========================================================================

    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/', [HumanResourceControler::class, 'Index'])->name('index');
        Route::get('/history', [HumanResourceControler::class, 'History'])->name('history');
        Route::get('/project/{id}', [HumanResourceControler::class, 'ProjectIndex'])->name('project.show');
        Route::post('/project/create', [HumanResourceControler::class, 'TransactionCreate'])->name('project.create');
        Route::post('/project/delete', [HumanResourceControler::class, 'TransactionDelete'])->name('project.delete');
        Route::post('/project/sign', [HumanResourceControler::class, 'TransactionSign'])->name('project.sign');
    });

    // ========================================================================
    // NURSE - User Routes
    // ========================================================================

    Route::prefix('nurse')->name('nurse.')->group(function () {
        Route::get('/', [NurseController::class, 'Index'])->name('index');
        Route::get('/history', [NurseController::class, 'History'])->name('history');
        Route::get('/project/{id}', [NurseController::class, 'ProjectIndex'])->name('project.show');
        Route::post('/project/create', [NurseController::class, 'TransactionCreate'])->name('project.create');
        Route::post('/project/delete', [NurseController::class, 'TransactionDelete'])->name('project.delete');
        Route::post('/project/sign', [NurseController::class, 'TransactionSign'])->name('project.sign');
    });

    // ========================================================================
    // TRAINING - User Routes
    // ========================================================================

    Route::prefix('training')->name('training.')->group(function () {
        Route::get('/', [TrainingController::class, 'Index'])->name('index');
        Route::get('/history', [TrainingController::class, 'history'])->name('history');
        Route::post('/getsession', [TrainingController::class, 'indexGetSessions'])->name('get.sessions');
        Route::post('/gettime', [TrainingController::class, 'indexgetTimes'])->name('get.times');
        Route::post('/register', [TrainingController::class, 'indexRegister'])->name('register');
        Route::post('/checkin', [TrainingController::class, 'indexCheckIn'])->name('checkin');
        Route::post('/change-registration', [TrainingController::class, 'changeRegistration'])->name('change.registration');
    });
});

// ============================================================================
// HRD ADMIN ROUTES
// ============================================================================

Route::middleware([HrAdmin::class])->group(function () {

    // ========================================================================
    // HRD ADMIN - Project Management
    // ========================================================================

    Route::prefix('hrd/admin')->name('hrd.admin.')->group(function () {
        Route::get('/', [HRController::class, 'adminIndex'])->name('index');
        Route::get('/documentation', [HRController::class, 'adminDocumentation'])->name('documentation');

        // Project CRUD Routes
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/create', [HRController::class, 'adminProjectCreate'])->name('create');
            Route::post('/', [HRController::class, 'adminProjectStore'])->name('store');

            Route::prefix('{id}')->group(function () {
                Route::get('/', [HRController::class, 'adminProjectShow'])->name('show');
                Route::get('/edit', [HRController::class, 'adminProjectEdit'])->name('edit');
                Route::post('/update', [HRController::class, 'adminProjectUpdate'])->name('update');
                Route::post('/delete', [HRController::class, 'adminProjectDelete'])->name('delete');

                // Registration Management
                Route::prefix('registrations')->name('registrations.')->group(function () {
                    Route::get('/', [HRController::class, 'adminProjectRegistrations'])->name('index');
                    Route::post('/', [HRController::class, 'adminRegistrationStore'])->name('store');
                    Route::put('/{registrationId}', [HRController::class, 'adminRegistrationUpdate'])->name('update');
                    Route::delete('/{registrationId}', [HRController::class, 'adminRegistrationDelete'])->name('delete');
                });

                // Approval Management
                Route::prefix('approvals')->name('approvals.')->group(function () {
                    Route::get('/', [HRController::class, 'adminProjectApprovals'])->name('index');
                    Route::post('/bulk-approve', [HRController::class, 'adminBulkApprove'])->name('bulk_approve');
                });

                Route::post('/approve-registration', [HRController::class, 'adminApproveRegistration'])->name('approve_registration');
                Route::post('/unapprove-registration', [HRController::class, 'adminUnapproveRegistration'])->name('unapprove_registration');

                // Seat Assignment Management
                Route::prefix('seat')->name('seat.')->group(function () {
                    Route::get('/management', [HRController::class, 'adminProjectSeatManagement'])->name('management');
                    Route::post('/assign', [HRController::class, 'assignManualSeat'])->name('assign');
                    Route::delete('/remove', [HRController::class, 'removeManualSeat'])->name('remove');
                    Route::delete('/clear', [HRController::class, 'clearTimeSeats'])->name('clear');
                });

                // Group Management
                Route::prefix('groups')->name('groups.')->group(function () {
                    Route::get('/', [HRController::class, 'adminProjectGroups'])->name('index');
                    Route::post('/', [HRController::class, 'adminGroupStore'])->name('store');
                    Route::put('/{groupId}', [HRController::class, 'adminGroupUpdate'])->name('update');
                    Route::delete('/{groupId}', [HRController::class, 'adminGroupDelete'])->name('delete');
                    Route::post('/import', [HRController::class, 'adminGroupImport'])->name('import');
                    Route::get('/template', [HRController::class, 'adminGroupTemplate'])->name('template');
                });

                // Result Management
                Route::prefix('results')->name('results.')->group(function () {
                    Route::get('/', [HRController::class, 'adminProjectResults'])->name('index');
                    Route::post('/import', [HRController::class, 'adminProjectResultsImport'])->name('import');
                    Route::get('/template', [HRController::class, 'adminProjectResultsTemplate'])->name('template');
                    Route::post('/clear', [HRController::class, 'adminProjectResultsClear'])->name('clear');
                });
            });
        });

        // Seat Assignment System Routes
        Route::prefix('seats')->name('seats.')->group(function () {
            Route::post('/trigger-assignment', [HRController::class, 'triggerSeatAssignment'])->name('trigger_assignment');
            Route::get('/{id}', [HRController::class, 'getProjectSeats'])->name('get');
            Route::post('/{id}/cleanup', [HRController::class, 'cleanupDuplicateSeats'])->name('cleanup');
        });

        // Export Routes
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/excel/all_date/{project_id}', [HRController::class, 'exportAllDateRegistrations'])->name('excel.all_date');
            Route::get('/excel/onebook/{project_id}', [HRController::class, 'exportOnebook'])->name('excel.onebook');
            Route::get('/excel/dbd/{project_id}', [HRController::class, 'exportDBD'])->name('excel.dbd');
            Route::get('/excel/date/{date_id}', [HRController::class, 'exportDateRegistrations'])->name('excel.date');
            Route::get('/pdf/time/{time_id}', [HRController::class, 'exportTimePDF'])->name('pdf.time');
        });

        // User Management Routes
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [HRController::class, 'adminUsers'])->name('index');
            Route::get('/{userId}/attendances', [HRController::class, 'adminUserAttendances'])->name('attendances');
            Route::post('/search', [HRController::class, 'adminUserSearch'])->name('search');
            Route::post('/resetpassword', [HRController::class, 'adminUserResetPassword'])->name('resetpassword');
        });
    });

    // ========================================================================
    // HR ADMIN - Legacy Routes
    // ========================================================================

    Route::prefix('hr/admin')->name('hr.admin.')->group(function () {
        Route::get('/', [HumanResourceControler::class, 'adminIndex'])->name('index');
        Route::get('/users', [CoreController::class, 'AllUserHR'])->name('users');
        Route::post('/users/search', [CoreController::class, 'UserSearch'])->name('users.search');
        Route::post('/resetpassword', [CoreController::class, 'UserResetPassword'])->name('resetpassword');
        Route::get('/createDEV', [HumanResourceControler::class, 'adminProjectCreate'])->name('create');
        Route::get('/function', [HumanResourceControler::class, 'addDatetoProject'])->name('function');

        // Project Management
        Route::prefix('project')->name('project.')->group(function () {
            Route::get('/{id}', [HumanResourceControler::class, 'adminProjectManagement'])->name('management');
            Route::get('/link/{id}', [HumanResourceControler::class, 'adminProjectLink'])->name('link');
            Route::post('/link/update', [HumanResourceControler::class, 'adminProjectLinkUpdate'])->name('link.update');
            Route::get('/transactions/{id}', [HumanResourceControler::class, 'adminProjectTransactions'])->name('transactions');
            Route::post('/createTransaction', [HumanResourceControler::class, 'adminProjectCreateTransaction'])->name('createTransaction');
            Route::post('/deleteTransaction', [HumanResourceControler::class, 'adminProjectDeleteTransaction'])->name('deleteTransaction');
        });

        // Approval Management
        Route::prefix('approve')->name('approve.')->group(function () {
            Route::get('/', [HumanResourceControler::class, 'adminProjectApprove'])->name('index');
            Route::post('/user', [HumanResourceControler::class, 'adminProjectApproveUser'])->name('user');
            Route::post('/userArray', [HumanResourceControler::class, 'adminProjectApproveUserArray'])->name('userArray');
        });

        // Export Routes
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/pdf/time/{item_id}', [HumanResourceControler::class, 'PDFTimeExport'])->name('pdf.time');
            Route::get('/excel/date/{slot_id}', [HumanResourceControler::class, 'ExcelDateExport'])->name('excel.date');
            Route::get('/excel/all_date/{project_id}', [HumanResourceControler::class, 'ExcelAllDateExport'])->name('excel.all_date');
            Route::get('/excel/onebook/{project_id}', [HumanResourceControler::class, 'ExcelOneBookExport'])->name('excel.onebook');
            Route::get('/excel/dbd/{project_id}', [HumanResourceControler::class, 'ExcelDBDExport'])->name('excel.dbd');
        });

        // Scores Management
        Route::prefix('scores')->name('scores.')->group(function () {
            Route::get('/', [HumanResourceControler::class, 'adminScores'])->name('index');
            Route::post('/import', [HumanResourceControler::class, 'ImportScore'])->name('import');
        });
    });

    // ========================================================================
    // TRAINING ADMIN ROUTES
    // ========================================================================

    Route::prefix('training/admin')->name('training.admin.')->group(function () {
        Route::get('/', [TrainingController::class, 'adminIndex'])->name('index');

        // Development Routes
        Route::prefix('dev')->name('dev.')->group(function () {
            Route::get('/delete', [TrainingController::class, 'deleteTestData'])->name('delete');
            Route::get('/seed', [TrainingController::class, 'seedData'])->name('seed');
        });

        // Approval Management
        Route::prefix('approve')->name('approve.')->group(function () {
            Route::get('/', [TrainingController::class, 'adminApprove'])->name('index');
            Route::post('/user', [TrainingController::class, 'adminApproveUser'])->name('user');
            Route::post('/users', [TrainingController::class, 'adminApproveUsers'])->name('users');
            Route::post('/teacher', [TrainingController::class, 'adminApproveUsersTeacher'])->name('teacher');
        });

        // Team Management
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::get('/', [TrainingController::class, 'adminTeamIndex'])->name('index');
            Route::get('/create', [TrainingController::class, 'adminTeamCreate'])->name('create');
            Route::post('/', [TrainingController::class, 'adminTeamStore'])->name('store');
            Route::get('/{id}/edit', [TrainingController::class, 'adminTeamEdit'])->name('edit');
            Route::post('/{id}/update', [TrainingController::class, 'adminTeamUpdate'])->name('update');
            Route::post('/{id}/delete', [TrainingController::class, 'adminTeamDelete'])->name('delete');
            Route::get('/{id}/teachers', [TrainingController::class, 'adminTeamTeachers'])->name('teachers');
        });

        // Teacher Management
        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::get('/create', [TrainingController::class, 'adminTeacherCreate'])->name('create');
            Route::post('/store', [TrainingController::class, 'adminTeacherStore'])->name('store');
            Route::get('/{id}/edit', [TrainingController::class, 'adminTeacherEdit'])->name('edit');
            Route::post('/{id}/update', [TrainingController::class, 'adminTeacherUpdate'])->name('update');
            Route::post('/{id}/delete', [TrainingController::class, 'adminTeacherDelete'])->name('delete');
            Route::get('/{id}/sessions', [TrainingController::class, 'adminTeacherSessions'])->name('sessions');
        });

        // Session Management
        Route::prefix('sessions')->name('sessions.')->group(function () {
            Route::get('/create', [TrainingController::class, 'adminSessionCreate'])->name('create');
            Route::post('/store', [TrainingController::class, 'adminSessionStore'])->name('store');
            Route::get('/{id}/edit', [TrainingController::class, 'adminSessionEdit'])->name('edit');
            Route::post('/{id}/update', [TrainingController::class, 'adminSessionUpdate'])->name('update');
            Route::post('/{id}/delete', [TrainingController::class, 'adminSessionDelete'])->name('delete');
        });

        // Time Management
        Route::prefix('times')->name('times.')->group(function () {
            Route::get('/create', [TrainingController::class, 'adminTimeCreate'])->name('create');
            Route::post('/store', [TrainingController::class, 'adminTimeStore'])->name('store');
            Route::get('/{id}/edit', [TrainingController::class, 'adminTimeEdit'])->name('edit');
            Route::post('/{id}/update', [TrainingController::class, 'adminTimeUpdate'])->name('update');
            Route::post('/{id}/delete', [TrainingController::class, 'adminTimeDelete'])->name('delete');
        });

        // Date Management
        Route::prefix('dates')->name('dates.')->group(function () {
            Route::get('/{time_id}', [TrainingController::class, 'adminDatesIndex'])->name('index');
            Route::get('/{time_id}/create', [TrainingController::class, 'adminDateCreate'])->name('create');
            Route::post('/{time_id}/store', [TrainingController::class, 'adminDateStore'])->name('store');
            Route::get('/{id}/edit', [TrainingController::class, 'adminDateEdit'])->name('edit');
            Route::post('/{id}/update', [TrainingController::class, 'adminDateUpdate'])->name('update');
            Route::post('/{id}/delete', [TrainingController::class, 'adminDateDelete'])->name('delete');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [TrainingController::class, 'adminUserIndex'])->name('index');
            Route::get('/create', [TrainingController::class, 'adminUserCreate'])->name('create');
            Route::post('/store', [TrainingController::class, 'adminUserStore'])->name('store');
            Route::post('/import', [TrainingController::class, 'adminUserImport'])->name('import');
            Route::post('/delete', [TrainingController::class, 'adminUserDelete'])->name('destroy');
        });

        // Registration Management
        Route::prefix('register')->name('register.')->group(function () {
            Route::get('/', [TrainingController::class, 'adminRegisterIndex'])->name('index');
            Route::post('/', [TrainingController::class, 'adminRegisterStore'])->name('store');
            Route::post('/unregister', [TrainingController::class, 'adminUnregisterUser'])->name('unregister');
        });

        // Move User Management
        Route::prefix('move')->name('move.')->group(function () {
            Route::get('/', [TrainingController::class, 'adminMoveUserIndex'])->name('index');
            Route::post('/user', [TrainingController::class, 'adminMoveUser'])->name('user');
            Route::post('/get-user-info', [TrainingController::class, 'adminGetUserInfo'])->name('get-user-info');
            Route::post('/get-available-times', [TrainingController::class, 'adminGetAvailableTimes'])->name('get-available-times');
        });

        // Export Management
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/index', [TrainingController::class, 'adminExportIndex'])->name('index');
            Route::get('/attends', [TrainingController::class, 'adminExportAttends'])->name('attends');
            Route::get('/hospitals', [TrainingController::class, 'adminExportHospitals'])->name('hospitals');
            Route::get('/onebooks', [TrainingController::class, 'adminExportOnebooks'])->name('onebooks');
        });
    });
});

// ============================================================================
// NURSE ADMIN ROUTES
// ============================================================================

Route::middleware([NurseAdmin::class])->group(function () {

    Route::prefix('nurse/admin')->name('nurse.admin.')->group(function () {
        Route::get('/', [NurseController::class, 'adminProjectIndex'])->name('index');

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [CoreController::class, 'AllUserNURSE'])->name('index');
            Route::post('/search', [CoreController::class, 'UserSearch'])->name('search');
            Route::post('/resetpassword', [CoreController::class, 'UserResetPassword'])->name('resetpassword');
        });

        // Project Management
        Route::prefix('project')->name('project.')->group(function () {
            Route::get('/{project_id}', [NurseController::class, 'adminProjectManagement'])->name('management');
            Route::post('/dateBetween', [NurseController::class, 'adminProjectDateBetween'])->name('dateBetween');
            Route::post('/deleteProject', [NurseController::class, 'adminProjectDelete'])->name('delete');
        });

        // Project CRUD
        Route::prefix('create')->name('create.')->group(function () {
            Route::get('/', [NurseController::class, 'adminProjectCreate'])->name('index');
            Route::post('/store', [NurseController::class, 'adminProjectStore'])->name('store');
        });

        // Transaction Management
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/{project_id}', [NurseController::class, 'adminProjectTransaction'])->name('index');
            Route::post('/create', [NurseController::class, 'adminProjectCreateTransaction'])->name('create');
            Route::post('/delete', [NurseController::class, 'adminProjectDeleteTransaction'])->name('delete');
        });

        // Approval Management
        Route::prefix('approve')->name('approve.')->group(function () {
            Route::get('/', [NurseController::class, 'adminProjectApprove'])->name('index');
            Route::post('/user', [NurseController::class, 'adminProjectApproveUser'])->name('user');
            Route::post('/userArray', [NurseController::class, 'adminProjectApproveUserArray'])->name('userArray');
        });

        // Lecture Management
        Route::prefix('lecture')->name('lecture.')->group(function () {
            Route::post('/add', [NurseController::class, 'adminAddLecture'])->name('add');
            Route::post('/delete', [NurseController::class, 'adminDeleteLecture'])->name('delete');
        });

        Route::post('/lecturer/update-score', [NurseController::class, 'updateLecturerScore'])->name('lecturer.update-score');

        // Export Routes
        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/excel/users/{project_id}', [NurseController::class, 'ExcelUserExport'])->name('excel.users');
            Route::get('/excel/lectures/{project_id}', [NurseController::class, 'ExcelLectureExport'])->name('excel.lectures');
            Route::get('/excel/datelecture/{date_id}', [NurseController::class, 'ExcelDateLectureExport'])->name('excel.datelecture');
            Route::get('/excel/dbd/{project_id}', [NurseController::class, 'ExcelDBDExport'])->name('excel.dbd');
            Route::get('/excel/type/{project_id}', [NurseController::class, 'ExcelTypeExport'])->name('excel.type');
            Route::get('/excel/date/users/{date_id}', [NurseController::class, 'ExcelDateUserExport'])->name('excel.date.users');
            Route::get('/excel/date/dbd/{date_id}', [NurseController::class, 'ExcelDateDBDExport'])->name('excel.date.dbd');
            Route::get('/excel/onebook/{date_id}', [NurseController::class, 'ExcelOneBookExport'])->name('excel.onebook');
        });

        // Score Management
        Route::prefix('score')->name('score.')->group(function () {
            Route::get('/users', [NurseController::class, 'UserScore'])->name('users');
            Route::get('/users/export/{department}', [NurseController::class, 'UserScoreExport'])->name('users.export');
        });
    });
});
