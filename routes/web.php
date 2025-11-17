<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/typo', function () {
    return view('elements.bc_typography');
})->name('typografi');
