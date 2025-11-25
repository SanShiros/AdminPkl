<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $items = PurchaseOrderItem::with(['purchaseOrder', 'product'])
            ->orderBy('id_po_item')
            ->paginate($perPage)
            ->withQueryString();

        $products = Product::all();
        $purchaseOrders = PurchaseOrder::all();

        return view('purchase_order_items.index', compact(
            'items',
            'perPage',
            'products',
            'purchaseOrders'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_po'      => 'required|exists:purchase_orders,id',
            'id_produk'  => 'required|exists:products,id_produk',
            'qty'        => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->qty * $request->harga_beli;

        PurchaseOrderItem::create([
            'id_po'      => $request->id_po,
            'id_produk'  => $request->id_produk,
            'qty'        => $request->qty,
            'harga_beli' => $request->harga_beli,
            'subtotal'   => $subtotal,
        ]);

        return redirect()
            ->route('purchase_order_items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function update(Request $request, PurchaseOrderItem $purchaseOrderItem)
    {
        $request->validate([
            'id_po'      => 'required|exists:purchase_orders,id',
            'id_produk'  => 'required|exists:products,id_produk',
            'qty'        => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ]);

        $subtotal = $request->qty * $request->harga_beli;

        $purchaseOrderItem->update([
            'id_po'      => $request->id_po,
            'id_produk'  => $request->id_produk,
            'qty'        => $request->qty,
            'harga_beli' => $request->harga_beli,
            'subtotal'   => $subtotal,
        ]);

        return redirect()
            ->route('purchase_order_items.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(PurchaseOrderItem $purchaseOrderItem)
    {
        $purchaseOrderItem->delete();

        return redirect()
            ->route('purchase_order_items.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}