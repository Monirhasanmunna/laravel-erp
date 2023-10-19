<?php

use App\Http\Controllers\Accounts\AccountController;
use App\Http\Controllers\Accounts\AccountDashboardController;
use App\Http\Controllers\Accounts\AccountsFeesController;
use App\Http\Controllers\Accounts\AccountsFeesTypeController;
use App\Http\Controllers\Accounts\ExpenseController;
use App\Http\Controllers\Accounts\FeesSetupController;
use App\Http\Controllers\Accounts\InvoiceReportController;
use App\Http\Controllers\Accounts\PaymentController;
use App\Http\Controllers\Accounts\PaymentReportController;
use App\Http\Controllers\Accounts\ReportsController;
use App\Http\Controllers\Accounts\ScholershipController;

use App\Http\Controllers\Accounts\StudentPaySlipController;
use App\Http\Controllers\Accounts\TeacherSecAssignController;
use App\Http\Controllers\Accounts\VendorController;
use App\Http\Controllers\Settings\PaymentGatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/accountsmanagement'], function () {

    Route::get('/dashboard', [AccountDashboardController::class, 'index'])->name('accountsmanagement.dashboard');
    Route::get('/dashboard/accounts-overview', [AccountDashboardController::class, 'overview'])->name('accountsmanagement.overview');
    
    Route::get('/get-dashboard-data-today', [AccountDashboardController::class, 'getDashboardDataToday'])->name('get-account-dashboard-data-today');
    Route::get('/get-dashboard-data', [AccountDashboardController::class, 'getDashboardData'])->name('get-account-dashboard-data');
    Route::get('/get-dashboard-data-by-session', [AccountDashboardController::class, 'getDashboardDataBySession'])->name('get-account-dashboard-data-by-session');
    Route::get('/get-overview-data-by-seesion-month', [AccountDashboardController::class, 'getOverviewDataBySessionMonth'])->name('get-account-overview-data-session-month');
    Route::get('/get-overview-data', [AccountDashboardController::class, 'getOverviewData'])->name('get-account-overview-data');
    // Fees
    Route::get('/student/fees', [AccountsFeesController::class, 'studentindex'])->name('accountsmanagement.index');
    Route::get('/student/fees/create', [AccountsFeesController::class, 'studentcreate'])->name('fees.create');
    Route::post('/student/fees/store', [AccountsFeesController::class, 'studentstore'])->name('fees.store');
    Route::get('/student/fees/edit/{id}', [AccountsFeesController::class, 'studentedit'])->name('fees.edit');
    Route::put('/student/fees/update/{id}', [AccountsFeesController::class, 'studentupdate'])->name('fees.update');
    Route::post('/student/fees/delete/{id}', [AccountsFeesController::class, 'studentdestroy'])->name('fees.destory');


    Route::get('/general/fees', [AccountsFeesController::class, 'generalindex'])->name('accountsmanagement.general.index');
    Route::get('/general/fees/create', [AccountsFeesController::class, 'generalcreate'])->name('fees.general.create');
    Route::post('/general/fees/store', [AccountsFeesController::class, 'generalstore'])->name('fees.general.store');
    Route::get('/general/fees/edit/{id}', [AccountsFeesController::class, 'generaledit'])->name('fees.general.edit');
    Route::put('/general/fees/update/{id}', [AccountsFeesController::class, 'generalupdate'])->name('fees.general.update');
    Route::post('/general/fees/delete/{id}', [AccountsFeesController::class, 'generalestroy'])->name('fees.general.destory');

    //Fees Setup
    Route::group(['as' => 'fees-setup.', 'prefix' => 'fees-setup'], function () {

        Route::get('/', [FeesSetupController::class, 'index'])->name('index');
        Route::get('/create', [FeesSetupController::class, 'create'])->name('create');
        Route::post('/store', [FeesSetupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FeesSetupController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [FeesSetupController::class, 'update'])->name('update');
    });

    //ajax get route
    Route::get('/get-fees-date', [FeesSetupController::class, 'getFeesData'])->name('get-fees-data');


    //Payment
    Route::group(['as' => 'payment.', 'prefix' => 'payment'], function () {
        Route::get('/index', [PaymentController::class, 'index'])->name('index');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('edit');
        Route::get('/{id}/show', [PaymentController::class, 'show'])->name('show');
        Route::post('/update/{id}', [PaymentController::class, 'update'])->name('update');
        Route::post('/{id}/destroy', [PaymentController::class, 'destroy'])->name('destroy');

        Route::get('/download-invoice/{id}', [PaymentController::class, 'downloadInvoice'])->name('download-invoice');
        //ajax routes
        Route::get('get-student-payments-by-month', [PaymentController::class, 'getStudentPayments'])->name('get-student-payments-by-month');
        Route::get('view-payments/{student_id}/{month}', [PaymentController::class, 'viewPayments'])->name('view-payments');
        Route::get('view-invoice/{student_id}/{month}', [PaymentController::class, 'viewInvoices'])->name('view-invoice');


        Route::get('/get-student-payments', [PaymentController::class, 'getStudentPaymentList'])->name('get-student-payment-list');
    });

    //student Payslip
    Route::group(['as' => 'student-payslip.', 'prefix' => 'student-payslip'], function () {

        Route::get('/index', [StudentPaySlipController::class, 'index'])->name('index');

        Route::get('/download-invoice/{id}/{months}', [StudentPaySlipController::class, 'downloadInvoice'])->name('download-invoice');
        Route::get('/download-invoice-all', [StudentPaySlipController::class, 'downloadInvoiceAll'])->name('download-invoice-all');

        //ajax routes
        Route::get('/get-student-payments-by-month', [StudentPaySlipController::class, 'getStudentPaymentByMonth'])->name('get-student-payments-by-month');
    });


    Route::group(['as' => 'payment-report.', 'prefix' => 'payment-report'], function () {

        Route::get('/paid', [PaymentReportController::class, 'index'])->name('index');
        Route::get('/paid-report', [PaymentReportController::class, 'paidReport'])->name('paid-report');
        Route::post('/paid-report-print', [PaymentReportController::class, 'paidReportPrint'])->name('paid-report-print');

        Route::get('/download-report/{id}/{month}', [PaymentReportController::class, 'downloadReport'])->name('download-report');

        Route::get('/unpaid', [PaymentReportController::class, 'unpaid'])->name('unpaid');
        Route::get('/unpaid-report', [PaymentReportController::class, 'unpaidReport'])->name('unpaid-report');
    });

    Route::group(['as' => 'reports.', 'prefix' => 'reports'], function () {

        Route::get('/expense', [ReportsController::class, 'expenseReport'])->name('expense-report');

        Route::get('/invoice-date-to-date', [InvoiceReportController::class, 'index'])->name('invoice-date-to-date');
        Route::post('/invoice-date-to-date-post', [InvoiceReportController::class, 'store'])->name('invoice-date-to-date.store');
        //Ajax
        Route::get('/get-invoice', [InvoiceReportController::class, 'getInvoices'])->name('get-invoice-list');

        //yearly report
        Route::get('yearly-payment-report', [InvoiceReportController::class, 'yearlyReport'])->name('yearly-report');
        Route::get('yearly-payment-report-print/{request}', [InvoiceReportController::class, 'yearlyReportPrint'])->name('yearly-report-print');
        Route::get('get-yearly-payment-report', [InvoiceReportController::class, 'getYearlyReport'])->name('get-yearly-report');

        //monthly report
        Route::get('monthly-payment-report', [InvoiceReportController::class, 'monthlyReport'])->name('monthly-report');
        Route::get('get-monthly-payment-report', [InvoiceReportController::class, 'getMonthlyReport'])->name('get-monthly-report');
        Route::get('monthly-payment-report-print/{request}', [InvoiceReportController::class, 'monthlyReportPrint'])->name('monthly-report-print');




    });

    Route::group(['as' => 'scholarship.', 'prefix' => 'scholarship'], function () {

        Route::get('/index', [ScholershipController::class, 'index'])->name('index');
        Route::get('/create', [ScholershipController::class, 'create'])->name('create');
        Route::post('/store', [ScholershipController::class, 'store'])->name('store');

        //ajax routes
        Route::get('/fee-details', [ScholershipController::class, 'getFeeDetails'])->name('get-fee-details');
        Route::get('/scholarship-details', [ScholershipController::class, 'getScholarshipDetails'])->name('get-scholarship-details');
    });

    Route::group(['as' => 'vendor.', 'prefix' => 'vendor'], function () {

        Route::get('/index', [VendorController::class, 'index'])->name('index');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::post('/store', [VendorController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [VendorController::class, 'delete'])->name('delete');
    });


    Route::group(['as' => 'account.', 'prefix' => 'account'], function () {

        Route::get('/index', [AccountController::class, 'index'])->name('index');
        Route::get('/create', [AccountController::class, 'create'])->name('create');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
        Route::get('/delete/{id}', [AccountController::class, 'delete'])->name('delete');
    });

    Route::group(['as' => 'expense.', 'prefix' => 'expense'], function () {

        Route::get('/index', [ExpenseController::class, 'index'])->name('index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('create');
        Route::post('/store', [ExpenseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ExpenseController::class, 'update'])->name('update');
        Route::get('/download-invoice/{id}', [ExpenseController::class, 'downloadInvoice'])->name('download-invoice');

        Route::post('/get-expense-details', [ExpenseController::class, 'expenseDetails'])->name('get-expense-details');
    });

    // Fees Type
    Route::get('/feestype', [AccountsFeesTypeController::class, 'index'])->name('feestype.index');
    Route::get('/feestype/create', [AccountsFeesTypeController::class, 'create'])->name('feestype.create');
    Route::post('/feestype/store', [AccountsFeesTypeController::class, 'store'])->name('feestype.store');
    Route::get('/feestype/edit/{id}', [AccountsFeesTypeController::class, 'edit'])->name('feestype.edit');
    Route::post('/feestype/update/{id}', [AccountsFeesTypeController::class, 'update'])->name('feestype.update');
    Route::post('/feestype/delete/{id}', [AccountsFeesTypeController::class, 'destroy'])->name('feestype.destory');
    Route::get('/multi-report', [MultiReportController::class, 'multi_report_input'])->name('multi-report');
    Route::get('/multi-report-output', [MultiReportController::class, 'multi_report_output'])->name('multi-report-output');

    Route::group(['as' => 'accounts.teacher-assign.', 'prefix' => 'teacher-assign'], function () {

        Route::get('/index', [TeacherSecAssignController::class, 'index'])->name('index');
        Route::get('/create', [TeacherSecAssignController::class, 'create'])->name('create');
        Route::post('/store', [TeacherSecAssignController::class, 'store'])->name('store');
        //ajax
        Route::get('/get-sections', [TeacherSecAssignController::class, 'getSections'])->name('get-sections');
        Route::get('/get-selected-sections', [TeacherSecAssignController::class, 'getSelectedSections'])->name('get-selected-sections');

    });
});

Route::group(['as' => 'payment-gateway.', 'prefix' => 'payment-gateway'], function () {

    Route::get('/index', [PaymentGatewayController::class, 'index'])->name('index');
});
