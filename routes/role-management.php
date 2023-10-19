<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;




Route::group(['prefix' => 'role-management','as' => 'role-management.'],function (){

    Route::group(['prefix' => 'roles','as' => 'roles.'],function (){
        Route::get('/index',[\App\Http\Controllers\RoleController::class,'index'])->name('index');
        Route::get('/create',[\App\Http\Controllers\RoleController::class,'create'])->name('create');
        Route::post('/store',[\App\Http\Controllers\RoleController::class,'store'])->name('store');
        Route::get('/show/{id}',[\App\Http\Controllers\RoleController::class,'show'])->name('show');
        Route::get('/edit/{id}',[\App\Http\Controllers\RoleController::class,'edit'])->name('edit');
        Route::post('/update/{id}',[\App\Http\Controllers\RoleController::class,'update'])->name('update');
        Route::get('/delete/{id}',[\App\Http\Controllers\RoleController::class,'delete'])->name('delete');
    });

    Route::group(['prefix' => 'users','as' => 'users.'],function (){
        Route::get('/index',[UserController::class,'index'])->name('index');
        Route::get('/create',[UserController::class,'create'])->name('create');
        Route::post('/store',[UserController::class,'store'])->name('store');
        Route::get('/edit/{id}',[UserController::class,'edit'])->name('edit');
        Route::post('/update/{id}',[UserController::class,'update'])->name('update');
        Route::get('/delete/{id}',[UserController::class,'delete'])->name('delete');

        Route::post('/password-reset',[UserController::class,'passwordReset'])->name('reset');
    });

});
