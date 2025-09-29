<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Dashboard\ExpenseController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('/user-dashboard', [UserDashboardController::class, 'index']);
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('expenses/import', [ExpenseController::class, 'showImportForm'])->name('expenses.import.form');
    Route::post('expenses/import', [ExpenseController::class, 'import'])->name('expenses.import');
    Route::resource('expenses', ExpenseController::class);

    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('users', UserController::class);
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });
});
