<?php
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;

// user-dashboard routes
Route::prefix('/')->middleware('web')->group(function () {
    Route::get('/', [CoreController::class, 'home2'])->name('home');
});
Route::get('login', fn() => redirect()->route('backend.login'))->name('login');

// ==== Backend (admin) ====
Route::prefix('backend')->name('backend.')->middleware(['web','firewall'])->group(function () {

    // Guest-only
    Route::middleware('guest')->group(function () {
        Route::get('login',    [AuthController::class, 'showLogin'])->name('login');          // GET  /backend/login
        Route::post('login',   [AuthController::class, 'login'])->name('login.action');       // POST /backend/login

        Route::get('register', [AuthController::class, 'showRegister'])->name('register');    // GET  /backend/register
        Route::post('register',[AuthController::class, 'register'])->name('register.action'); // POST /backend/register
    });

    // Auth-only
    Route::middleware('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout'])->name('logout');            // POST /backend/logout
        Route::get('dashboard',[CoreController::class, 'home'])->name('dashboard');           // GET  /backend/dashboard
    });

});

