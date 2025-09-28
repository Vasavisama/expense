<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Dashboard\ExpenseController;

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
    Route::post('expenses/export', [\App\Http\Controllers\Dashboard\ExpenseController::class, 'export'])->name('expenses.export');
    Route::resource('expenses', ExpenseController::class);
});

Route::middleware(['jwt', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboardController::class, 'users'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminDashboardController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminDashboardController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggleStatus');
    Route::get('/expenses', [AdminDashboardController::class, 'expenses'])->name('expenses.index');
    Route::post('/expenses/export', [AdminDashboardController::class, 'exportAll'])->name('expenses.exportAll');
});

