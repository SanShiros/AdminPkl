<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 3);

        $purchaseOrders = PurchaseOrder::with('supplier')
            ->orderBy('id', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();

        return view('purchase_orders.index', compact('purchaseOrders', 'perPage', 'suppliers'));
    }

    public function create()
    {
        // kita pakai modal, jadi ini boleh kosong
    }

    public function store(Request $request)
    {
        // SIMPAN DATA BARU
        $validated = $request->validate([
        'kode_po'     => 'required|string|max:50|unique:purchase_orders,kode_po',
        'id_supplier' => 'required|exists:suppliers,id',
        'tanggal'     => 'required|date',
        'total'       => 'required|numeric|min:0',
        'status'      => 'required|in:draft,purchase,selesai', // <-- penting
    ]);

    PurchaseOrder::create($validated);

    return redirect()
        ->route('purchase_orders.index')
        ->with('success', 'Purchase order berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        // juga pakai modal, boleh kosong
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        // UPDATE DATA EXISTING
        $validated = $request->validate([
        'kode_po'     => 'required|string|max:50|unique:purchase_orders,kode_po,' . $purchaseOrder->id,
        'id_supplier' => 'required|exists:suppliers,id',
        'tanggal'     => 'required|date',
        'total'       => 'required|numeric|min:0',
        'status'      => 'required|in:draft,purchase,selesai', // sama
    ]);

    $purchaseOrder->update($validated);

    return redirect()
        ->route('purchase_orders.index')
        ->with('success', 'Purchase order berhasil diupdate.');
}

 public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();

        return redirect()
            ->route('purchase_orders.index')
            ->with('success', 'Purchase order berhasil dihapus.');
    }
}
