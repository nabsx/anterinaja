<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Driver\AuthController as DriverAuthController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Driver\OrderController as DriverOrderController;
use App\Http\Controllers\Driver\ProfileController as DriverProfileController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->group(function () {
    // Authentication
    Route::middleware('guest')->group(function () {
        Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [UserAuthController::class, 'register']);
    });

    // Authenticated Routes
    Route::middleware(['auth', 'user'])->group(function () {
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [UserOrderController::class, 'dashboard'])->name('dashboard');
        
        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [UserOrderController::class, 'index'])->name('orders.index');
            Route::get('/create', [UserOrderController::class, 'create'])->name('orders.create');
            Route::post('/', [UserOrderController::class, 'store'])->name('orders.store');
            Route::get('/{order}', [UserOrderController::class, 'show'])->name('orders.show');
            Route::post('/{order}/cancel', [UserOrderController::class, 'cancel'])->name('orders.cancel');
        });
        
        // Profile
        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Driver Routes
|--------------------------------------------------------------------------
*/

Route::prefix('driver')->name('driver.')->group(function () {
    // Authentication
    Route::middleware('guest:driver')->group(function () {
        Route::get('/login', [DriverAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [DriverAuthController::class, 'login']);
    });

    // Authenticated Routes
    Route::middleware(['auth', 'driver'])->group(function () {
        Route::post('/logout', [DriverAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');
        Route::post('/status', [DriverDashboardController::class, 'updateStatus'])->name('status.update');
        Route::post('/location', [DriverDashboardController::class, 'updateLocation'])->name('location.update');
        
        // Orders
        Route::prefix('orders')->group(function () {
            Route::get('/', [DriverOrderController::class, 'index'])->name('orders.index');
            Route::get('/available', [DriverOrderController::class, 'availableOrders'])->name('orders.available');
            Route::post('/{order}/accept', [DriverOrderController::class, 'acceptOrder'])->name('orders.accept');
            Route::post('/{order}/status', [DriverOrderController::class, 'updateStatus'])->name('orders.status.update');
            Route::get('/{order}', [DriverOrderController::class, 'show'])->name('orders.show');
        });
        
        // Profile
        Route::get('/profile', [DriverProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [DriverProfileController::class, 'update'])->name('profile.update');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    // Authenticated Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', AdminUserController::class)->except(['show']);
        
        // Driver Management
        Route::resource('drivers', AdminDriverController::class);
        Route::post('/drivers/{driver}/verify', [AdminDriverController::class, 'verify'])->name('drivers.verify');
        
        // Order Management
        Route::resource('orders', AdminOrderController::class);
        Route::get('/reports', [AdminOrderController::class, 'reports'])->name('reports');
    });
});