<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TwoFactorController;

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

Route::view('/', 'home.index')->name('home')->middleware(['auth', 'twofactor']);
Route::view('/somelink', 'somelink.index')->name('somelink')->middleware(['auth', 'twofactor']);

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::middleware(['guest', 'twofactor'])->group(function () {
    Route::get('register', [RegisterController::class, 'index'])->name('register')->middleware();
    Route::post('register', [RegisterController::class, 'store'])->name('register.store')->middleware();

    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['twofactor','auth'])->group(function () {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::post('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::get('verify', [TwoFactorController::class, 'index'])->name('verify.index');
    Route::post('verify', [TwoFactorController::class, 'store'])->name('verify.store');
});
