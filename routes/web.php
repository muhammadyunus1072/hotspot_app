<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([], __DIR__ . '/web/Auth.php');
Route::group([], __DIR__ . '/web/Other.php');
// Admin
Route::group([], __DIR__ . '/web/Admin/Account.php');
Route::group([], __DIR__ . '/web/Admin/Product.php');
Route::group([], __DIR__ . '/web/Admin/Transaction.php');
// Member
Route::group([], __DIR__ . '/web/Member/Transaction.php');

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => DashboardController::class, "prefix" => "dashboard", "as" => "dashboard."], function () {
        Route::get('/', 'index')->name('index');
    });
});
