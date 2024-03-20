<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::group(['prefix' => 'cart'], function () {
    Route::post('/', [CartController::class, 'store'])->name('cart.addproduct');
    Route::delete('/{id}', [CartController::class, 'destroy'])->name('cart.deleteproduct');
});



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['verified'])->name('dashboard');

    Route::group(['prefix' => 'product', 'middleware' =>  ['role:admin|editor']], function () {
        Route::post('/', [ProductController::class, 'store'])->name('product.post');
        Route::put('/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::post('/{id}', [ProductController::class, 'uploade'])->name('product.uploade');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product.delete');
    });

    Route::group(['prefix'=> 'users', 'middleware' =>  ['role:admin']], function(){
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::post('/', [UsersController::class, 'store'])->name('users.add');
        Route::put('/', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.delete');

        // Route::get('test', function () {
        //     Auth::user()->assignRole('admin');
        // });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// require __DIR__.'/product.php';
require __DIR__ . '/auth.php';
