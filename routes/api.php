<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;

// Version control
Route::prefix('v1')->group(function (){
    Route::get('/page_setting', [GeneralController::class, 'getComponentList']);
    Route::get('/brands', [GeneralController::class, 'getBrandsList']);
    Route::get('/brands/{id}/{slug?}', [GeneralController::class, 'getBrand']);

    Route::prefix('admin')->group(function () {
        Route::post('create_component', [AdminController::class, 'createComponent']);
        Route::post('update_component/{id}', [AdminController::class, 'updateComponent']);
        Route::post('create_brand', [AdminController::class, 'createBrand']);
    });
});