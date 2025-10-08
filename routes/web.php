<?php

use App\Http\Controllers\Dashboard\BlogSeminarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\SeminarController;
use App\Http\Controllers\Dashboard\BookingController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\CartController as UserCartController;

// ===================== AUTH =====================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// ===================== USER =====================
Route::prefix('users')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [UserUserController::class, 'index'])->name('user.home');
    Route::middleware(['role:User'])->group(function () {
        Route::get('/about', [UserUserController::class, 'about'])->name('user.about');
        Route::get('/products', [UserUserController::class, 'product'])->name('user.products.index');
        Route::get('/order-history', [UserUserController::class, 'orders'])->name('user.order-history.index');
        Route::get('/product/{id}', [UserUserController::class, 'detailProduct'])->name('user.products.detail');
        Route::post('/products/{id}/order', [UserOrderController::class, 'directOrder'])->name('products.order');
        Route::get('/konsultasi', [UserUserController::class, 'konsultasi'])->name('user.konsultasi.index');
        Route::post('/konsultasi/create', [UserUserController::class, 'store'])->name('user.konsultasi.store');
        Route::get('/blogs', [UserUserController::class, 'blog'])->name('user.blogs.index');
        Route::get('/blog/{id}', [UserUserController::class, 'detailBlog'])->name('user.blogs.detail');
        Route::get('/seminars', [UserUserController::class, 'seminar'])->name('user.seminars.index');
        Route::get('/seminar/{id}', [UserUserController::class, 'detailSeminar'])->name('user.seminars.detail');
        Route::post('/order/direct/{id}', [UserOrderController::class, 'directOrder'])->name('order.direct');
        Route::post('/bookings', [UserUserController::class, 'store'])->name('bookings.store');
        Route::post('/cart/checkout', [UserCartController::class, 'checkout'])->name('user.cart.checkout');
        Route::get('/order/success/{orderId}', [UserOrderController::class, 'success'])->name('order.success');
        Route::get('/order/pending/{orderId}', [UserOrderController::class, 'pending'])->name('order.pending');
        Route::get('/cart', [UserCartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [UserCartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/{itemId}', [UserCartController::class, 'remove'])->name('cart.remove');
        Route::put('/cart/{itemId}', [UserCartController::class, 'update'])->name('cart.update');
    });
});

// ===================== DASHBOARD =====================
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // ========== ADMIN ==========
    Route::get('/', function () {
        return view('dashboard.pages.dashboard');
    })->name('admin.dashboard');

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('dashboard.users');
        Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole'])->name('dashboard.users.assignRole');
        Route::delete('/users/{id}/roles/{roleName}', [UserController::class, 'destroyRole'])->name('dashboard.users.destroyRole');
        Route::post('/permissions/store', [UserController::class, 'storePermission'])->name('dashboard.permissions.store');
        Route::post('/permissions/assign', [UserController::class, 'assignPermission'])->name('dashboard.permissions.assign');
        Route::get('/booking', [BookingController::class, 'index'])->name('bookings.index');
        Route::put('/booking/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    // ========== GRAPHIC DESIGNER ==========
    Route::middleware(['role:Graphic Designer|Admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
        Route::resource('/blogs', BlogController::class)->only(['index', 'create', 'store', 'show', 'update']);
        Route::resource('/seminars', SeminarController::class)->only(['create', 'store', 'edit', 'update']);
    });

    // ========== CONTENT CREATOR ==========
    Route::middleware(['role:Content Creator|Admin'])->group(function () {
        Route::resource('/blogs', BlogController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('/seminars', [SeminarController::class, 'index'])->name('seminars.index');
        Route::get('/blog-seminar', [BlogSeminarController::class, 'index'])->name('blog_seminar.index');
        Route::get('/seminars/{id}', [SeminarController::class, 'show'])->name('seminars.show');
        Route::put('/seminars/{seminar}', [SeminarController::class, 'update'])->name('seminars.update');
        // Route::resource('/seminars', SeminarController::class)->only(['index','create', 'store', 'show']);
    });

    // ========== CINEMATOGRAPHER ==========
    Route::middleware(['role:Cinematographer|Admin'])->group(function () {
        Route::resource('/seminars', SeminarController::class)->only(['create', 'store', 'edit', 'update']);
    });

    // ========== DATA ANALYST & SURVEYOR ==========
    Route::middleware(['role:Data Analyst|Surveyor & Research|Admin'])->group(function () {
        Route::resource('/products', ProductController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy']);
        Route::get('/nutrition/{id}/analyze', [ProductController::class, 'analyzeNutrition'])->name('products.nutrition.analyze');
    });

    // ========== PUBLIC SPEAKER ==========
    Route::middleware(['role:Public Speaker|Admin'])->group(function () {
        Route::get('/seminars', [SeminarController::class, 'index'])->name('seminars.index');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/seminars/{id}/host', [SeminarController::class, 'host'])->name('seminars.host');
    });

    // ========== BUDGET PLANNER ==========
    Route::middleware(['role:Budget Planner|Admin'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/finance/reports', [OrderController::class, 'report'])->name('finance.report');
    });

    // ========== INVENTORY MANAGER ==========
    Route::middleware(['role:Inventory Manager|Admin'])->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
    });
});
