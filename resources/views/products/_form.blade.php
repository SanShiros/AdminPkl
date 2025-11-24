{{-- CSRF dan @method di-set di tempat yang include _form ini --}}

<div class="mb-3">
    <label class="form-label">Nama Produk</label>
    <input
        type="text"
        name="nama_produk"
        class="form-control @error('nama_produk') is-invalid @enderror"
        value="{{ old('nama_produk', $product->nama_produk ?? '') }}"
        required
    >
    @error('nama_produk')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">SKU</label>
    <input
        type="text"
        name="sku"
        class="form-control @error('sku') is-invalid @enderror"
        value="{{ old('sku', $product->sku ?? '') }}"
    >
    @error('sku')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select
        name="id_kategori"
        class="form-select @error('id_kategori') is-invalid @enderror"
        required
    >
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ old('id_kategori', $product->id_kategori ?? '') == $category->id ? 'selected' : '' }}
            >
                {{ $category->nama }}
            </option>
        @endforeach
    </select>
    @error('id_kategori')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Supplier Default</label>
    <select
        name="id_supplier_default"
        class="form-select @error('id_supplier_default') is-invalid @enderror"
    >
        <option value="">-- Tidak ada --</option>
        @foreach ($suppliers as $supplier)
            <option
                value="{{ $supplier->id }}"
                {{ old('id_supplier_default', $product->id_supplier_default ?? '') == $supplier->id ? 'selected' : '' }}
            >
                {{ $supplier->nama_supplier }}
            </option>
        @endforeach
    </select>
    @error('id_supplier_default')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Stok</label>
    <input
        type="number"
        name="stok"
        min="0"
        class="form-control @error('stok') is-invalid @enderror"
        value="{{ old('stok', $product->stok ?? 0) }}"
        required
    >
    @error('stok')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Harga Beli Terakhir</label>
    <input
        type="number"
        step="0.01"
        name="harga_beli_terakhir"
        class="form-control @error('harga_beli_terakhir') is-invalid @enderror"
        value="{{ old('harga_beli_terakhir', $product->harga_beli_terakhir ?? '') }}"
    >
    @error('harga_beli_terakhir')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Harga Jual</label>
    <input
        type="number"
        step="0.01"
        name="harga_jual"
        class="form-control @error('harga_jual') is-invalid @enderror"
        value="{{ old('harga_jual', $product->harga_jual ?? '') }}"
        required
    >
    @error('harga_jual')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
