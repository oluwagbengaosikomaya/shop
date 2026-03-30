<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [ShopController::class, 'index'])->name('shop.index');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/admin/login', [LoginController::class, 'showAdminLogin'])->name('admin.login.form');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// User Dashboard (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/my-orders/{id}', [\App\Http\Controllers\User\DashboardController::class, 'show'])->name('user.orders.show');
});

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    Route::get('admins', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admins.index');
    Route::get('admins/create', [\App\Http\Controllers\Admin\AdminUserController::class, 'create'])->name('admins.create');
    Route::post('admins', [\App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('admins.store');
    Route::delete('admins/{admin}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('admins.destroy');
});

// Cart Routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
});

Route::get('/cart/clear', function () {
    session()->forget('cart');
    return redirect('/')->with('success', 'Cart cleared');
});

Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
});
Route::get('/payment/verify', [CheckoutController::class, 'verifyPayment'])->name('payment.verify');