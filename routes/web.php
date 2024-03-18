<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::group(['prefix' => 'cart'], function () {
    Route::post('/', [CartController::class, 'store'])->name('cart.addproduct');
    Route::delete('/{id}', [CartController::class, 'destroy'])->name('cart.deleteproduct');
});

Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'product'], function () {
        Route::post('/', [ProductController::class, 'store'])->name('product.post');
        Route::put('/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/{id}', [ProductController::class, 'uploade'])->name('product.uploade');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product.delete');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// require __DIR__.'/product.php';
require __DIR__ . '/auth.php';
