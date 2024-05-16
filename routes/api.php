<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;

// Version control
Route::prefix('v1')->group(function (){
    Route::get('/page_setting', [GeneralController::class, 'getComponentList']);
    Route::get('/brands', [GeneralController::class, 'getBrandsList']);
    Route::get('/brands/{id}/{slug?}', [GeneralController::class, 'getBrand']);
    Route::get('/brand/{id}/products', [GeneralController::class, 'getBrandProducts']);
    Route::get('/product/{id}/{slug?}', [GeneralController::class, 'getProduct']);
    Route::get('/search', [GeneralController::class, 'getSearchProductList']);

    Route::prefix('user')->group(function () {

        Route::middleware(['guest'])->group(function () {
            Route::post('register', [UserController::class, 'register']);
            Route::post('login', [UserController::class, 'login']);
        });

        Route::middleware(['auth:api', 'user_checker'])->group(function () {
            Route::post('logout', [UserController::class, 'logout']);
            Route::post('update_profile', [UserController::class, 'updateProfile']);
            Route::post('update_user', [UserController::class, 'updateUser']);
            Route::get('profile', [UserController::class, 'getProfile']);
        });

    });

    Route::prefix('admin')->middleware([ 'auth:api', 'admin_checker' ])->group(function () {
        Route::post('create_component', [AdminController::class, 'createComponent']);
        Route::post('update_component/{id}', [AdminController::class, 'updateComponent']);
        Route::post('create_brand', [AdminController::class, 'createBrand']);
        Route::post('create_product', [AdminController::class, 'createProduct']);
        Route::post('create_product_images/{product_id}', [AdminController::class, 'createProductImages']);
        Route::post('create_product_options/{product_id}', [AdminController::class, 'createProductOptions']);
        Route::post('create_product_option_details/{product_id}', [AdminController::class, 'createProductOptionDetails']);
        Route::post('update_product/{product_id}/{status}', [AdminController::class, 'updateProductStatus']);
    });

    Route::prefix('cart')->middleware([ 'auth:api' ])->group(function () {
        Route::post('add', [CartController::class, 'addCart']);
        Route::get('list', [CartController::class, 'getCartList']);
        Route::post('adjustment', [CartController::class, 'adjustCartDetail']);
        Route::post('remove/{id}', [CartController::class, 'removeCartItem']);
        Route::post('batch_details', [CartController::class, 'batchGetCartDetail']);
    });
});