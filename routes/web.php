<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StockMovementController;

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

// CRUD Product
Route::resource('products', ProductController::class);

// CRUD Sales (POS)
Route::resource('sales', SalesController::class);

// CRUD Purchase
Route::resource('purchase_orders', PurchaseOrderController::class);
Route::resource('purchase_order_items', PurchaseOrderItemController::class);

// Contoh route lain
Route::get('/item', function () {
    return view('sales_item.index');
});

Route::resource('products', ProductController::class);

// QR CODE PRODUCT
Route::get('products/{product}/qr', [ProductController::class, 'qrInline'])
    ->name('products.qr');                // tampilkan gambar QR kecil (src img)

Route::get('products/{product}/qr/label', [ProductController::class, 'qrLabel'])
    ->name('products.qr.label');          // halaman print label

Route::get('products/{product}/qr/download/png', [ProductController::class, 'downloadPng'])
    ->name('products.qr.download.png');   // download PNG

Route::get('products/{product}/qr/download/pdf', [ProductController::class, 'downloadPdf'])
    ->name('products.qr.download.pdf');   // download PDF

Route::resource('sales', SalesController::class);

// API kecil untuk scan QR → cari produk pakai SKU
Route::get('/sales/product-by-sku/{sku}', [SalesController::class, 'findProductBySku'])
    ->name('sales.productBySku');



Route::get('/stock-movements', [StockMovementController::class, 'index'])
    ->name('stock_movements.index');
