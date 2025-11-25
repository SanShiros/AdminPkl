<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 3);

        $products = Product::with(['category', 'supplier'])
            ->orderBy('id_produk', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::orderBy('nama', 'asc')->get();
        $suppliers  = Supplier::orderBy('nama_supplier', 'asc')->get();

        return view('products.index', compact('products', 'perPage', 'categories', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validated = $request->validate([
            'nama_produk'          => 'required|string|max:255',
            'sku'                  => 'nullable|string|max:255|unique:products,sku',
            'id_kategori'          => 'required|exists:categories,id',
            'stok'                 => 'required|integer|min:0',
            'harga_beli_terakhir'  => 'nullable|numeric|min:0',
            'harga_jual'           => 'required|numeric|min:0',
            'id_supplier_default'  => 'nullable|exists:suppliers,id',
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        
       $validated = $request->validate([
            'nama_produk'          => 'required|string|max:255',
            'sku'                  => 'nullable|string|max:255|unique:products,sku,' . $product->id_produk . ',id_produk',
            'id_kategori'          => 'required|exists:categories,id',
            'stok'                 => 'required|integer|min:0',
            'harga_beli_terakhir'  => 'nullable|numeric|min:0',
            'harga_jual'           => 'required|numeric|min:0',
            'id_supplier_default'  => 'nullable|exists:suppliers,id',
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
    {
          $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    }

    public function qrInline(Product $product)
{
    if (! $product->sku) {
        abort(404, 'Produk tidak punya SKU');
    }

    $png = QrCode::format('png')
        ->size(200)
        ->margin(1)
        ->generate($product->sku);

    return response($png)->header('Content-Type', 'image/png');
}

public function qrLabel(Product $product)
{
    if (! $product->sku) {
        abort(404, 'Produk tidak punya SKU');
    }

    // QR untuk embed di blade
    $pngData = base64_encode(
        QrCode::format('png')->size(300)->margin(1)->generate($product->sku)
    );

    return view('products.qr_label', compact('product', 'pngData'));
}

public function downloadPng(Product $product)
{
    if (! $product->sku) {
        return back()->with('error', 'Produk belum memiliki SKU, tidak bisa buat QR.');
    }

    $png = QrCode::format('png')
        ->size(400)
        ->margin(1)
        ->generate($product->sku);

    $fileName = 'QR-'.$product->sku.'.png';

    return response($png)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
}

public function downloadPdf(Product $product)
{
    if (! $product->sku) {
        return back()->with('error', 'Produk belum memiliki SKU, tidak bisa buat QR.');
    }

    // generate base64 png
    $pngData = base64_encode(
        QrCode::format('png')->size(300)->margin(1)->generate($product->sku)
    );

    // simple HTML label, nanti diprint sebagai PDF oleh browser/user
    $html = view('products.qr_label_pdf', [
        'product' => $product,
        'pngData' => $pngData,
    ])->render();

    return response($html);
}

}
