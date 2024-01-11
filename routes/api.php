<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GeneralController;

Route::get('/homepage/carousel', [GeneralController::class, 'getComponentList']);

Route::prefix('admin')->group(function () {
    Route::post('create_component', [AdminController::class, 'createComponent']);
    Route::post('update_component/{id}', [AdminController::class, 'updateComponent']);
});