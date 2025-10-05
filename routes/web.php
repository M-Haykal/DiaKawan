<?php

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

// ===================== AUTH =====================
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================== USER =====================
Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('/', [UserUserController::class, 'index'])->name('user.home');
    Route::middleware(['role:User'])->group(function () {
        Route::get('/products', [UserUserController::class, 'product'])->name('user.products.index');
        Route::get('/blogs', [UserUserController::class, 'blog'])->name('user.blogs.index');
        Route::get('/blog/{id}', [UserUserController::class, 'detailBlog'])->name('user.blogs.detail');
        Route::get('/seminars', [UserUserController::class, 'seminar'])->name('user.seminars.index');
        Route::post('/order/direct/{id}', [UserOrderController::class, 'directOrder'])->name('order.direct');
        Route::post('/bookings', [UserUserController::class, 'store'])->name('bookings.store');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    });
});

// ===================== DASHBOARD =====================
Route::prefix('dashboard')->middleware('auth')->group(function () {

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
