<?php

use App\Http\Controllers\Student\AdmissionController;
use App\Http\Controllers\Student\BirthDayWishController;
use App\Http\Controllers\Student\MeritStudentController;
use App\Http\Controllers\Student\MigrationController;
use App\Http\Controllers\Student\OnlineAdmissionController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentProfileUpdateController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentBranchMigrationController;
use App\Http\Controllers\Student\StudentSubjectAssignController;
use App\Http\Controllers\Student\StudentSubjectUnassignedController;
use Illuminate\Support\Facades\Route;


Route::group(['as' => 'student.', 'prefix' => '/student/'], function () {

    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

    //get sections
    Route::get('/get-sections-by-session', [StudentController::class, 'getSectionsBySession'])->name('get-sections-by-session');

    Route::get('/index', [StudentController::class, 'index'])->name('index');
    Route::get('/list', [StudentController::class, 'list'])->name('list');
    Route::post('/store', [StudentController::class, 'store'])->name('store');
    Route::get('/create', [StudentController::class, 'create'])->name('create');
    Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('edit');
    Route::get('/{id}/show', [StudentController::class, 'show'])->name('show');
    Route::post('/update/{id}', [StudentController::class, 'update'])->name('update');
    Route::any('/destroy/{id}', [StudentController::class, 'destroy'])->name('destroy');

    Route::get('/export-students', [StudentController::class, 'exportStudents'])->name('export');
    Route::post('/export/print-students', [StudentController::class, 'exportPrintStudents'])->name('exportprint');
    Route::post('/export/pdf-students', [StudentController::class, 'exportPdfStudents'])->name('exportpdf');
    Route::get('/create-user/{id}', [StudentController::class, 'createUser'])->name('create-user');

    //ajax routes
    Route::get('/get-sections', [StudentController::class, 'getSections'])->name('get-sections');
    Route::get('/get-classes', [StudentController::class, 'getClasses'])->name('get-classes');
    Route::get('/get-categories-groups', [StudentController::class, 'getCategoriesGroups'])->name('get-categories-groups');

    Route::get('/get-class-shift-by-section', [StudentController::class, 'getClassShift'])->name('get-class-shift');
    Route::get('/getitembyshiftid/{id}', [StudentController::class, 'getitembyshiftid'])->name('getitembyshiftid');
    //student password update
    Route::post('/password-update', [StudentController::class, 'changePassword'])->name('change-password');

    Route::get('/getShiftbyClass/{class_id}', [StudentController::class, 'getShiftbyClass'])->name('getShiftbyClass');
    Route::get('/getCatSecGro/{class_id}/{section_id}', [StudentController::class, 'getCatSecGro'])->name('getCatSecGro');

    //ajax routes
    Route::get('/get-students', [StudentController::class, 'getStudents'])->name('get-students');
    Route::get('/get-admitted-students', [StudentController::class, 'getAdmitedStudents'])->name('get-admited-students');
    Route::get('/get-students-with-subjects', [StudentController::class, 'getStudentsWithSubjects'])->name('get-students-with-subjects');
    Route::get('/get-subject-checked', [StudentController::class, 'getSubjectChecked'])->name('get-subject-checked');
    Route::get('/get-students-with-assign-subjects', [StudentController::class, 'getStudentsWithAssignSubjects'])->name('get-students-with-assign-subjects');
    Route::get('/get-students-with-common-subjects', [StudentController::class, 'getStudentsWithCommonSubjects'])->name('get-students-with-common-subjects');
    Route::get('/delete-student-assign-subject', [StudentController::class, 'deleteStudentAssignSubject'])->name('delete-student-assign-subject');

    //migrationf
    Route::group(['as' => 'migration.', 'prefix' => 'migration'], function () {

        Route::get('/index', [MigrationController::class, 'index'])->name('index');
        Route::post('/store', [MigrationController::class, 'store'])->name('store');
    });

    //birthday-wish
    Route::group(['as' => 'birthday-wish.', 'prefix' => '/birthday-wish/'], function () {

        Route::any('/index', [BirthDayWishController::class, 'index'])->name('index');
        Route::post('/send-sms', [BirthDayWishController::class, 'sendMessage'])->name('sendMessage');
    });
    //Student Subject Assign
    Route::group(['as' => 'subject-assign.', 'prefix' => 'subject-assign'], function () {
        Route::get('/index', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'store'])->name('store');

        //print
        Route::get('/print/{data}', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'print'])->name('print');

        //bulk assign
        Route::get('/bulk', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'bulk'])->name('bulk');
        Route::post('/bulk-action', [\App\Http\Controllers\Student\StudentSubjectAssignController::class, 'bulkAction'])->name('bulk-action');
        //ajax routes
        Route::get('/get-students', [StudentSubjectAssignController::class, 'getStudents'])->name('get-students');
        Route::get('/get-subjects', [StudentSubjectAssignController::class, 'getSubjects'])->name('get-subjects');
    });
    //Student Subject unassigned
    Route::group(['as' => 'subject-unassigned.', 'prefix' => 'subject-unassigned'], function () {
        Route::get('/index', [\App\Http\Controllers\Student\StudentSubjectUnassignedController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Student\StudentSubjectUnassignedController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Student\StudentSubjectUnassignedController::class, 'store'])->name('store');

        //bulk unassigned
        Route::get('/bulk', [\App\Http\Controllers\Student\StudentSubjectUnassignedController::class, 'bulk'])->name('bulk');
        Route::post('/bulk-action', [\App\Http\Controllers\Student\StudentSubjectUnassignedController::class, 'bulkAction'])->name('bulk-action');
        //ajax routes
        Route::get('/get-students', [StudentSubjectUnassignedController::class, 'getStudents'])->name('get-students');
        Route::get('/get-subjects', [StudentSubjectUnassignedController::class, 'getSubjects'])->name('get-subjects');
    });

    Route::group(['as' => 'report.', 'prefix' => '/report'], function () {
        Route::get('/index', [ReportController::class, 'index'])->name('index');
        Route::get('/get-report/{session}', [ReportController::class, 'getReport']);
        Route::any('/pdf-export/{session}', [ReportController::class, 'ExportPdf'])->name('ExportPdf');
    });

    Route::group(['as' => 'online-admission.', 'prefix' => 'online/admission'], function () {
        Route::get('/list', [OnlineAdmissionController::class, 'index'])->name('list');
        Route::post('/store', [OnlineAdmissionController::class, 'store'])->name('store');
    });


    Route::group(['as' => 'branch-migration.', 'prefix' => 'branch-migration'], function () {

        Route::get('/index', [StudentBranchMigrationController::class, 'index'])->name('index');
        Route::post('/store', [StudentBranchMigrationController::class, 'store'])->name('store');
        Route::get('/get-students', [StudentBranchMigrationController::class, 'getStudents'])->name('get-students');

        Route::get('/student-list', [StudentBranchMigrationController::class, 'studentList'])->name('student-list');
        Route::get('/student-store', [StudentBranchMigrationController::class, 'studentStore'])->name('student-store');
    });
});


Route::group(['as' => 'admission.', 'prefix' => '/student/'], function () {
    Route::get('/admission', [AdmissionController::class, 'index'])->name('index');
    Route::get('/admission/create', [AdmissionController::class, 'create'])->name('create');
    Route::get('/admission/upload-create', [AdmissionController::class, 'uploadcreate'])->name('upload.create');
    Route::post('/admission', [AdmissionController::class, 'store'])->name('store');
    Route::post('/admission/uploadstore', [AdmissionController::class, 'uploadstore'])->name('upload.store');
    Route::put('/admission/{id}', [AdmissionController::class, 'update']);
    Route::get('/admission/upload', [AdmissionController::class, 'upload'])->name('upload');
    Route::post('/admission/upload', [AdmissionController::class, 'uploadcreatestore'])->name('uploadstore');
    Route::post('/admission/section', [AdmissionController::class, 'section'])->name('section_store');
    Route::get('/admission/{id}', [AdmissionController::class, 'destroy'])->name('destroy');
    Route::get('/admission/categories', [AdmissionController::class, 'show'])->name('categories');
    Route::post('/get/number/of/table/student', [AdmissionController::class, 'getNumberOfTable'])->name('get_number_of.table_student');
});



Route::group(['as' => 'studentprofile.', 'prefix' => '/student/'], function () {
    Route::get('/profile-update', [StudentProfileUpdateController::class, 'index'])->name('index');
    Route::post('/profile/update', [StudentProfileUpdateController::class, 'update'])->name('update');

    //ajax routes
    Route::any('/image-update/{id}', [StudentProfileUpdateController::class, 'imageUpdate'])->name('image-update');
});

Route::group(['as' => 'meritstudent.', 'prefix' => '/merit/student'], function () {
    Route::get('/index', [MeritStudentController::class, 'index'])->name('index');
    Route::get('/create', [MeritStudentController::class, 'create'])->name('create');
    Route::post('/store', [MeritStudentController::class, 'store'])->name('store');

    #Merit Student
});
