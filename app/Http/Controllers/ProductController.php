<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
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
}
