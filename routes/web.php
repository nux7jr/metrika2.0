<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\DataGridController;

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

Route::view('/', 'home.index', ["title" => 'Аналитика 2.0'])->name('home')->middleware(['auth', 'twofactor']);
Route::view('/visitors', 'visitors.index', ["title" => 'Аунтификация посетителей'])->name('visitors')->middleware(['auth', 'twofactor']);
Route::view('/week', 'week.index', ["title" => 'Еженедельный отчет'])->name('week')->middleware(['auth', 'twofactor']);
Route::view('/day', 'day.index', ["title" => 'Ежедневный отчет'])->name('day')->middleware(['auth', 'twofactor']);
Route::view('/conversion', 'conversion.index', ["title" => 'Конверсия'])->name('diagram')->middleware(['auth', 'twofactor']);
Route::view('/partners', 'partners.index', ["partners" => 'Ваша таблица'])->name('partners')->middleware(['auth', 'twofactor']);

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::middleware(['guest', 'twofactor'])->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});


Route::middleware(['twofactor', 'auth'], ["title" => 'Двухфакторная аутентификация'])->group(function () {
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::post('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::get('verify', [TwoFactorController::class, 'index'], ["title" => 'Двухфакторная аутентификация'])->name('verify.index');
    Route::post('verify', [TwoFactorController::class, 'store'])->name('verify.store');

    Route::get('create', [RegisterController::class, 'index', ["title" => 'Создание пользователя']],)->name('register')->middleware();
    Route::post('create', [RegisterController::class, 'store'])->name('register.store')->middleware();
    Route::put('create', [RegisterController::class, 'create'])->name('create_or_update');
    Route::delete('create',[RegisterController::class, 'delete'])->name('delete_user');
    Route::get('get_cities', [DataGridController::class, 'getCities'])->name('get_cities');
    Route::get('get_users', [RegisterController::class, 'getUsers'])->name('get_users');
    Route::get('get_roles', [RegisterController::class, 'getRoles'])->name('get_roles');

    Route::post('get_leads', [DataGridController::class, 'store'])->name('get_leads');
    Route::get('get_leads', [DataGridController::class, 'store'])->name('get_leads');

    Route::post('get_week_report', [DataGridController::class, 'week'])->name('get_week_report');
    Route::post('get_daily_report', [DataGridController::class, 'daily'])->name('get_daily_report');
});
