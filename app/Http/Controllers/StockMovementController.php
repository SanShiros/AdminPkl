<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('product')->orderBy('tanggal', 'desc');

        if ($request->filled('id_produk')) {
            $query->where('id_produk', $request->id_produk);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe); // IN / OUT
        }

        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber); // SALE / PURCHASE / ...
        }

        $movements = $query->paginate(20)->withQueryString();
        $products  = Product::orderBy('nama_produk')->get();

        return view('stock_movements.index', compact('movements', 'products'));
    }
}
