<?php

use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/suppliers', function () {
    return view('suppliers.index');
})->name('supplier');

Route::resource('suppliers', SupplierController::class);