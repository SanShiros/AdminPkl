<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderItemController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $items = PurchaseOrderItem::with(['purchaseOrder', 'product'])
            ->orderBy('id_po_item')
            ->paginate($perPage)
            ->withQueryString();

        $products       = Product::all();
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

        DB::transaction(function () use ($request, $subtotal) {
            // 1) Simpan item PO
            $item = PurchaseOrderItem::create([
                'id_po'      => $request->id_po,
                'id_produk'  => $request->id_produk,
                'qty'        => $request->qty,
                'harga_beli' => $request->harga_beli,
                'subtotal'   => $subtotal,
            ]);

            // 2) Tambah stok produk
            $product = Product::find($item->id_produk);
            if ($product) {
                $product->stok = $product->stok + $item->qty;
                $product->save();
            }

            // 3) Stock movement IN
            StockMovement::create([
                'id_produk'  => $item->id_produk,
                'tanggal'    => now(),
                'tipe'       => 'IN',
                'qty'        => $item->qty,
                'sumber'     => 'PURCHASE',
                'id_ref'     => $item->id_po,
                'keterangan' => 'Item PO #' . $item->id_po,
            ]);
        });

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

        DB::transaction(function () use ($request, $purchaseOrderItem, $subtotal) {
            $oldProductId = $purchaseOrderItem->id_produk;
            $oldQty       = $purchaseOrderItem->qty;

            $newProductId = $request->id_produk;
            $newQty       = $request->qty;

            // 1) Update item dulu
            $purchaseOrderItem->update([
                'id_po'      => $request->id_po,
                'id_produk'  => $newProductId,
                'qty'        => $newQty,
                'harga_beli' => $request->harga_beli,
                'subtotal'   => $subtotal,
            ]);

            // 2) Koreksi stok & stock movement
            // Kasus A: produk TIDAK berubah
            if ($oldProductId == $newProductId) {
                $diff = $newQty - $oldQty;

                if ($diff != 0) {
                    $product = Product::find($newProductId);
                    if ($product) {
                        $product->stok = $product->stok + $diff; // diff bisa + atau -
                        $product->save();

                        StockMovement::create([
                            'id_produk'  => $newProductId,
                            'tanggal'    => now(),
                            'tipe'       => $diff > 0 ? 'IN' : 'OUT',
                            'qty'        => abs($diff),
                            'sumber'     => 'PURCHASE_ADJUST',
                            'id_ref'     => $purchaseOrderItem->id_po,
                            'keterangan' => 'Penyesuaian qty PO item #' . $purchaseOrderItem->id_po,
                        ]);
                    }
                }
            } else {
                // Kasus B: produk BERUBAH
                // 2B-1: kembalikan stok produk lama (keluarkan)
                $oldProduct = Product::find($oldProductId);
                if ($oldProduct) {
                    $oldProduct->stok = max(0, $oldProduct->stok - $oldQty);
                    $oldProduct->save();

                    StockMovement::create([
                        'id_produk'  => $oldProductId,
                        'tanggal'    => now(),
                        'tipe'       => 'OUT',
                        'qty'        => $oldQty,
                        'sumber'     => 'PURCHASE_ADJUST',
                        'id_ref'     => $purchaseOrderItem->id_po,
                        'keterangan' => 'Pindah item dari produk lama di PO #' . $purchaseOrderItem->id_po,
                    ]);
                }

                // 2B-2: tambah stok ke produk baru
                $newProduct = Product::find($newProductId);
                if ($newProduct) {
                    $newProduct->stok = $newProduct->stok + $newQty;
                    $newProduct->save();

                    StockMovement::create([
                        'id_produk'  => $newProductId,
                        'tanggal'    => now(),
                        'tipe'       => 'IN',
                        'qty'        => $newQty,
                        'sumber'     => 'PURCHASE_ADJUST',
                        'id_ref'     => $purchaseOrderItem->id_po,
                        'keterangan' => 'Pindah item ke produk baru di PO #' . $purchaseOrderItem->id_po,
                    ]);
                }
            }
        });

        return redirect()
            ->route('purchase_order_items.index')
            ->with('success', 'Item berhasil diupdate.');
    }

    public function destroy(PurchaseOrderItem $purchaseOrderItem)
    {
        DB::transaction(function () use ($purchaseOrderItem) {
            $productId = $purchaseOrderItem->id_produk;
            $qty       = $purchaseOrderItem->qty;
            $idPo      = $purchaseOrderItem->id_po;

            // 1) Kurangi stok lagi (karena item dihapus)
            $product = Product::find($productId);
            if ($product) {
                $product->stok = max(0, $product->stok - $qty);
                $product->save();

                StockMovement::create([
                    'id_produk'  => $productId,
                    'tanggal'    => now(),
                    'tipe'       => 'OUT',
                    'qty'        => $qty,
                    'sumber'     => 'PURCHASE_DELETE',
                    'id_ref'     => $idPo,
                    'keterangan' => 'Hapus item dari PO #' . $idPo,
                ]);
            }

            // 2) Hapus item
            $purchaseOrderItem->delete();
        });

        return redirect()
            ->route('purchase_order_items.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}
