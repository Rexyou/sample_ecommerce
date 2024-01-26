<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;

// Version control
Route::prefix('v1')->group(function (){
    Route::get('/page_setting', [GeneralController::class, 'getComponentList']);
    Route::get('/brands', [GeneralController::class, 'getBrandsList']);
    Route::get('/brands/{id}/{slug?}', [GeneralController::class, 'getBrand']);
    Route::get('/product/{id}/{slug?}', [GeneralController::class, 'getProduct']);

    Route::prefix('user')->group(function () {

        Route::middleware(['guest'])->group(function () {
            Route::post('register', [UserController::class, 'register']);
        });

    });

    Route::prefix('admin')->group(function () {
        Route::post('create_component', [AdminController::class, 'createComponent']);
        Route::post('update_component/{id}', [AdminController::class, 'updateComponent']);
        Route::post('create_brand', [AdminController::class, 'createBrand']);
        Route::post('create_product', [AdminController::class, 'createProduct']);
        Route::post('create_product_images/{product_id}', [AdminController::class, 'createProductImages']);
        Route::post('create_product_options/{product_id}', [AdminController::class, 'createProductOptions']);
        Route::post('create_product_option_details/{product_id}', [AdminController::class, 'createProductOptionDetails']);
    });
});