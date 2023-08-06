<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\BvnValidController;

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
Route::get('/api/documentation', function () {
    return view('swagger');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::post('/signup', [RegisterController::class, 'signup_v'])->name('signup');
Route::post('/login', [LoginController::class, 'login_'])->name('login-ath');
Route::post('/bvn-auth', [BvnValidController::class, 'bvn'])->name('bvn-auth');
Route::get('/', [DashboardController::class,'index'])->name('welcome');
