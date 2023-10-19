<?php

use App\Http\Controllers\TeacherPanel\AccountDashboardController;
use App\Http\Controllers\TeacherPanel\ExamDashboardController;
use App\Http\Controllers\TeacherPanel\FeesCollectionController;
use App\Http\Controllers\TeacherPanel\TeacherPanelController;
use App\Http\Controllers\TeacherPanel\HomeWorkController;
use App\Http\Controllers\TeacherPanel\MarksInputController;
use App\Http\Controllers\TeacherPanel\MCQController;
use App\Http\Controllers\TeacherPanel\QuestionChapterController;
use App\Http\Controllers\TeacherPanel\TeacherLeaveApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'teacherpanel', 'as' => 'teacherpanel.', 'middleware' => 'auth:teacher'], function () {


    Route::get('/', [TeacherPanelController::class, 'index'])->name('index');

    Route::group(['as' => 'exam-management.', 'prefix' => 'exam-management'], function () {

        Route::group(['as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
            Route::get('/', [ExamDashboardController::class, 'index'])->name('index');
        });

        Route::group(['as' => 'marks-input.', 'prefix' => 'marks-input'], function () {

            Route::get('/', [MarksInputController::class, 'index'])->name('index');
            Route::get('/create', [MarksInputController::class, 'create'])->name('create');
            Route::get('/get-subjects', [MarksInputController::class, 'getSubjects'])->name('get-subjects');
            Route::post('/store', [MarksInputController::class, 'store'])->name('store');

            Route::get('/upload-excel', [MarksInputController::class, 'uploadExcel'])->name('upload-excel');
            Route::post('/upload-excel-insert', [MarksInputController::class, 'uploadExcelInsert'])->name('upload-excel-insert');
            Route::post('/upload-excel-store', [MarksInputController::class, 'uploadExcelStore'])->name('upload-excel-store');
        });
    });


    Route::group(['as' => 'account-management.', 'prefix' => 'account-management'], function () {

        Route::group(['as' => 'dashboard.', 'prefix' => 'dashboard'], function () {
            Route::get('/', [AccountDashboardController::class, 'index'])->name('index');
        });

        Route::group(['as' => 'collection.', 'prefix' => 'collection'], function () {

            Route::get('/', [FeesCollectionController::class, 'index'])->name('index');
            Route::post('/store', [FeesCollectionController::class, 'store'])->name('store');
            Route::get('/download-invoice/{id}', [FeesCollectionController::class, 'downloadInvoice'])->name('download-invoice');


            Route::get('view-payments/{student_id}/{month}', [FeesCollectionController::class, 'viewPayments'])->name('view-payments');
            Route::get('view-invoice/{student_id}/{month}', [FeesCollectionController::class, 'viewInvoices'])->name('view-invoice');
            //ajac routes
            Route::get('/get-sections', [FeesCollectionController::class, 'getSections'])->name('get-sections');
            Route::get('/get-cat-groups', [FeesCollectionController::class, 'getCatGroup'])->name('get-cat-groups');
            Route::get('/get-payments', [FeesCollectionController::class, 'getStudentPayments'])->name('get-payments');
            Route::get('/get-student-payments', [FeesCollectionController::class, 'getStudentPaymentList'])->name('get-student-payment-list');
            
        });

    });



    Route::group(['as' => 'homework.', 'prefix' => 'homework'], function () {

        Route::get('/', [HomeWorkController::class, 'index'])->name('index');
        Route::get('/create', [HomeWorkController::class, 'create'])->name('create');
        Route::post('/store', [HomeWorkController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [HomeWorkController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [HomeWorkController::class, 'update'])->name('update');
        Route::post('/delete/{id}', [HomeWorkController::class, 'destroy'])->name('destory');
    });

    Route::group(['as' => 'application.', 'prefix' => 'leave-application'], function () {
        Route::get('/', [TeacherLeaveApplicationController::class, 'index'])->name('index');
        Route::get('/create', [TeacherLeaveApplicationController::class, 'create'])->name('create');
        Route::post('/store', [TeacherLeaveApplicationController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TeacherLeaveApplicationController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [TeacherLeaveApplicationController::class, 'update'])->name('update');
        Route::any('/delete/{id}', [TeacherLeaveApplicationController::class, 'destroy'])->name('destory');

        // ajax route
        Route::get('/template-details/{id}', [TeacherLeaveApplicationController::class, 'getTemplateById']);
    });


    Route::group(['as' => 'question.', 'prefix' => 'question'], function () {
        Route::get('/', [QuestionChapterController::class, 'index'])->name('index');
        Route::get('/create', [QuestionChapterController::class, 'create'])->name('create');
        Route::post('/store', [QuestionChapterController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [QuestionChapterController::class, 'edit'])->name('edit');
        Route::get('/show/{id}', [QuestionChapterController::class, 'show'])->name('show');
        Route::put('/update/{id}', [QuestionChapterController::class, 'update'])->name('update');
        Route::any('/delete/{id}', [QuestionChapterController::class, 'destroy'])->name('destory');

        // ajax route
        Route::get('/subject-select', [QuestionChapterController::class, 'selectSubject'])->name('select_sub');
        Route::get('/subject-list/{id}', [QuestionChapterController::class, 'getSubjectListById']);



        Route::group(['as' => 'mcqquestion.', 'prefix' => 'mcq-question'], function () {
            Route::get('/', [MCQController::class, 'index'])->name('index');
            Route::get('/create', [MCQController::class, 'create'])->name('create');
            Route::post('/store', [MCQController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [MCQController::class, 'edit'])->name('edit');
            Route::get('/show/{id}', [MCQController::class, 'show'])->name('show');
            Route::put('/update/{id}', [MCQController::class, 'update'])->name('update');
            Route::any('/delete/{id}', [MCQController::class, 'destroy'])->name('destory');

            // ajax route
            Route::get('/subject-select', [MCQController::class, 'selectSubject'])->name('select_sub');
            Route::get('/subject-list/{id}', [MCQController::class, 'getSubjectListById']);
        });
    });
});
