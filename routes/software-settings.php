<?php

use App\Http\Controllers\Academic\IdCardController;
use App\Http\Controllers\SoftwareSettings\ImportDataController;
use App\Http\Controllers\SoftwareSettings\RecycleBinController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoftwareSettings\IdcardThemeController;
use App\Http\Controllers\SoftwareSettings\SystemThemeController;


Route::group(['as'=>'software-settings.','prefix'=>'software-settings'],function (){

    Route::group(['as'=>'system-theme.','prefix'=>'system-theme/'],function () {
        Route::get('/',[SystemThemeController::class,'index'])->name('index');
        Route::get('/themeUpdate/{id}',[SystemThemeController::class,'theamUpdate'])->name('theamUpdate');
    });

    Route::group(['as'=>'idcardtheme.','prefix'=>'theme/idcard'],function(){
        Route::get('/',[IdcardThemeController::class,'index'])->name('index');
        Route::get('/create',[IdcardThemeController::class,'create'])->name('create');


        Route::get('/themeUpdate/{id}',[IdcardThemeController::class,'theamUpdate'])->name('theamUpdate');
        Route::get('/demo-front/{id}',[IdcardThemeController::class,'demoDownloadFront'])->name('demoDownloadFront');
        Route::get('/demo-back/{id}',[IdcardThemeController::class,'demoDownloadBack'])->name('demoDownloadBack');
    });

    Route::group(['as'=>'import-data.','prefix'=>'import-data'],function(){

        Route::get('/index',[ImportDataController::class,'index'])->name('index');
        Route::get('/import/{module}',[ImportDataController::class,'import'])->name('import');

    });

    Route::group(['as'=>'recycle-bin.','prefix'=>'recycle-bin'],function(){
        Route::get('/',[RecycleBinController::class,'index'])->name('index');
        //ajax routes
        Route::get('/get-data',[RecycleBinController::class,'getDataByModel'])->name('get-data');
        Route::get('/restore-data',[RecycleBinController::class,'restoreDataByModel'])->name('restore-data');
    });

});

