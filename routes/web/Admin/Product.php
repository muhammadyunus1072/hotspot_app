<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\MonthlyHotspotController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => ProductController::class, "prefix" => "product", "as" => "product."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => MonthlyHotspotController::class, "prefix" => "monthly_hotspot", "as" => "monthly_hotspot."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
});
