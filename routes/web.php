<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//user router
Route::get('/', [UserController::class, 'index'])->name('user.home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// add to cart
Route::prefix('cart')->controller(CartController::class)->group(function() {
    Route::get('view', 'view')->name('cart.view');
    Route::post('store/{product}', 'store')->name('cart.store');
    Route::patch('update/{product}', 'update')->name('cart.update');
    Route::delete('delete/{product}', 'delete')->name('cart.delete');
});

//admin router
Route::group(['prefix' =>'admin', 'middleware'=>'redirectAdmin'], function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('products/upload-image', [ProductController::class, 'uploadImage'])->name('admin.products.upload-image');
    Route::delete('products/revert-image/{uniqueId}', [ProductController::class, 'revertImage'])->name('admin.products.revert-image');
    Route::post('products/store', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('products/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('products/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

});

require __DIR__.'/auth.php';