<?php

use App\Http\Controllers\Ins\InstitutionController;
use App\Http\Controllers\InstallController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can add more routes...
|
*/

Route::controller(InstitutionController::class)
    ->prefix('/institutes')
    ->name('institutes.')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
    });

Route::get('table-remove',[InstallController::class,'webCreate']);
 Route::get('table-update',[InstallController::class,'webUpdate']);
