<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\UserController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\HotspotMemberController;
use App\Http\Controllers\Transaction\PaymentMethodController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => HotspotMemberController::class, "prefix" => "hotspot_member", "as" => "hotspot_member."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');

        Route::get('/user/get', [UserController::class, 'search_member'])->name('get.user');
        Route::get('/monthly_hotspot/get', [ProductController::class, 'search_monthly_hotspot'])->name('get.monthly_hotspot');
    });

    Route::group(["controller" => PaymentMethodController::class, "prefix" => "payment_method", "as" => "payment_method."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    
    Route::group(["controller" => TransactionController::class, "prefix" => "transaction", "as" => "transaction."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');

        Route::get('/user/get', [UserController::class, 'search'])->name('get.user');
        Route::get('/product/get', [ProductController::class, 'search_product'])->name('get.product');
        Route::get('/payment_method/get', [PaymentMethodController::class, 'search'])->name('get.payment_method');
    });
});
