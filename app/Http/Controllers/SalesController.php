<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sales;
use App\Models\User;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
     public function index()
    {
        $sales = Sales::with('user')->orderBy('id_sale', 'DESC')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $users = User::all();
        $products = Product::all();
        $defaultKodeNota = 'SL-' . now()->format('YmdHis');

        return view('sales.create', compact('users', 'products', 'defaultKodeNota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_nota'     => 'required|string|max:50|unique:sales,kode_nota',
            'tanggal'       => 'required|date',
            'total'         => 'required|numeric|min:0',
            'bayar'         => 'required|numeric|min:0',
            'kembalian'     => 'nullable|numeric|min:0',
            'metode_bayar'  => 'nullable|string|max:50',
            'id_user'       => 'nullable|exists:users,id',
            'keranjang'     => 'required|json'
        ]);

        $keranjang = json_decode($request->keranjang, true);

        $kembalian = $request->kembalian ?? ($request->bayar - $request->total);

        $id_qty   = array_column($keranjang, 'qty', 'id');
        $itemIds  = array_keys($id_qty);

        $existingItemIds = Product::whereIn('id_produk', $itemIds)->pluck('id_produk')->toArray();
        $missingItems    = array_diff($itemIds, $existingItemIds);

        if (!empty($missingItems)) {
            return redirect()->route('sales.create')->with('error', 'Item(s) no longer exist in inventory.');
        }

        DB::transaction(function () use ($request, $keranjang, $kembalian) {
            // 1) Simpan header sales
            $sale = Sales::create([
                'kode_nota'     => $request->kode_nota,
                'tanggal'       => $request->tanggal,
                'total'         => $request->total,
                'bayar'         => $request->bayar,
                'kembalian'     => $kembalian,
                'metode_bayar'  => $request->metode_bayar,
                'id_user'       => $request->id_user
            ]);

            // 2) Simpan detail ke sales_products
            $pivot = [];
            foreach ($keranjang as $item) {
                $pivot[] = [
                    'id_product'  => $item['id'],
                    'id_sale'     => $sale->id_sale,
                    'qty'         => $item['qty'],
                    'subtotal'    => $item['qty'] * $item['harga_jual'],
                    'harga_jual'  => $item['harga_jual'],
                ];
            }

            DB::table('sales_products')->insert($pivot);

            // 3) Kurangi stok + catat stock movement OUT
            foreach ($keranjang as $item) {
                // Kurangi stok
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stok = max(0, $product->stok - $item['qty']);
                    $product->save();
                }

                // Stock movement OUT
                StockMovement::create([
                    'id_produk'  => $item['id'],
                    'tanggal'    => $request->tanggal ?? now(),
                    'tipe'       => 'OUT',
                    'qty'        => $item['qty'],
                    'sumber'     => 'SALE',
                    'id_ref'     => $sale->id_sale,
                    'keterangan' => 'Penjualan ' . $sale->kode_nota,
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sales berhasil ditambahkan!');
    }

    
    public function edit($id)
    {
        $sale = Sales::findOrFail($id);
        $users = User::all();

        return view('sales.edit', compact('sale', 'users'));
    }

    /**
     * Update data sales
     */
    public function update(Request $request, $id)
    {
        $sale = Sales::findOrFail($id);

        $request->validate([
            'kode_nota' => 'required|string|max:50|unique:sales,kode_nota,' . $id . ',id_sale',
            'tanggal'       => 'required|date',
            'total'         => 'required|numeric|min:0',
            'bayar'         => 'required|numeric|min:0',
            'kembalian'     => 'nullable|numeric|min:0',
            'metode_bayar'  => 'nullable|string|max:50',
            'id_user'       => 'nullable|exists:users,id'
        ]);

        // Hitung kembalian otomatis
        $kembalian = $request->kembalian ?? ($request->bayar - $request->total);

        $sale->update([
            'kode_nota'     => $request->kode_nota,
            'tanggal'       => $request->tanggal,
            'total'         => $request->total,
            'bayar'         => $request->bayar,
            'kembalian'     => $kembalian,
            'metode_bayar'  => $request->metode_bayar,
            'id_user'       => $request->id_user
        ]);

        return redirect()->route('sales.index')->with('success', 'Sales berhasil diupdate!');
    }

    /**
     * Hapus sales
     */
    public function destroy($id)
    {
        $sale = Sales::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sales berhasil dihapus!');
    }

    public function findProductBySku($sku)
    {
        $product = \App\Models\Product::where('sku', $sku)->first();

        if (! $product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan untuk SKU: ' . $sku,
            ], 404);
        }

        return response()->json([
            'id'    => $product->id_produk,
            'nama'  => $product->nama_produk,
            'harga' => $product->harga_jual,
        ]);
    }
}
