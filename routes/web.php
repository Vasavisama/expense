<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Dashboard\ExpenseController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register']);




Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::middleware('jwt')->group(function () {
    Route::get('/user-dashboard', [UserDashboardController::class, 'index']);
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);

    Route::middleware('admin')->group(function () {
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });
});