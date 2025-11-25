<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Tampilkan semua data sales
     */
    public function index()
    {
        $sales = Sales::with('user')->orderBy('id_sales', 'DESC')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Halaman create sales
     */
    public function create()
    {
        $users = User::all();
        return view('sales.create', compact('users'));
    }

    /**
     * Simpan sales baru
     */
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
            'keranjang' => 'required|json'
        ]);

        $keranjang = json_decode($request->keranjang, true);

        // Hitung kembalian otomatis jika tidak diinput
        $kembalian = $request->kembalian ?? ($request->bayar - $request->total);

        $id_qty = array_column($keranjang, 'qty', 'id');
        $itemIds = array_keys($id_qty);
        // I hate hate hate hate hate hate hate hate 
        $existingItemIds = Item::whereIn('id', $itemIds)->pluck('id')->toArray();
        $missingItems = array_diff($itemIds, $existingItemIds);

        if (!empty($missingItems)) {
            return redirect()->route('sales.create')->with('error', 'Item(s) no longer exist in inventory.');
        }

        $sale = Sales::create([
            'kode_nota'     => $request->kode_nota,
            'tanggal'       => $request->tanggal,
            'total'         => $request->total,
            'bayar'         => $request->bayar,
            'kembalian'     => $kembalian,
            'metode_bayar'  => $request->metode_bayar,
            'id_user'       => $request->id_user
        ]);

        $pivot = [];
        foreach ($keranjang as $item) {
            $pivot[] = [
                'id_product' => $item['id'],
                'id_sale' => $sale->id,
                'qty' => $item['qty'],
                'subtotal' => $item['qty'] * $item['harga_jual'],
                'harga_jual' => $item['harga_jual'],
            ];
        }

        DB::table('sales_products')->insert($pivot);

        return redirect()->route('sales.index')->with('success', 'Sales berhasil ditambahkan!');
    }

    /**
     * Halaman edit sales
     */
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
            'kode_nota'     => 'required|string|max:50|unique:sales,kode_nota,' . $id . ',id_sales',
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
}
