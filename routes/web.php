<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

Route::get('/index', function () {
    if (!session()->has('user')) {
        return redirect()->route('login');
    }
    return view('index');
})->name('index');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// CRUD Supplier
Route::resource('suppliers', SupplierController::class);

// CRUD Category
Route::resource('categories', CategoryController::class);
