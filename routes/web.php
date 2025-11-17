<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/suppliers', function () {
    return view('elements.supplier');
})->name('supplier');

