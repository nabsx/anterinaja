<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/create-order', [UserController::class, 'createOrder'])->name('create-order');
    Route::post('/orders', [UserController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [UserController::class, 'showOrder'])->name('orders.show');
});

// Driver routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/toggle-online', [DriverController::class, 'toggleOnlineStatus'])->name('toggle-online');
    Route::post('/update-location', [DriverController::class, 'updateLocation'])->name('update-location');
    Route::post('/accept-order/{id}', [DriverController::class, 'acceptOrder'])->name('accept-order');
    Route::post('/update-order-status/{id}', [DriverController::class, 'updateOrderStatus'])->name('update-order-status');
    Route::get('/order-history', [DriverController::class, 'orderHistory'])->name('order-history');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('orders.show');
});