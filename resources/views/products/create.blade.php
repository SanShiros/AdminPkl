@extends('layouts.sidebar')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Tambah Produk</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ul>
                </div>
            </div>

            {{-- ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups!</strong> Ada beberapa kesalahan:<br>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="{{ old('nama_produk') }}"
                                required>
                        </div>

                        {{-- SKU + tombol scan --}}
                        <div class="mb-3">
                            <label class="form-label">SKU / Kode Barcode</label>
                            <div class="input-group">
                                <input type="text" name="sku" id="sku-input" class="form-control"
                                    value="{{ old('sku') }}" required>
                                <button type="button" class="btn btn-outline-secondary" id="btn-open-sku-scanner">
                                    Scan
                                </button>
                            </div>
                            <small class="text-muted">
                                Arahkan kamera ke barcode / QR di kemasan untuk mengisi SKU otomatis.
                            </small>
                        </div>

                        {{-- CARD SCANNER (muncul hanya saat tombol Scan diklik) --}}
                        <div id="sku-scanner-card" class="card border-0 shadow-sm mb-3 d-none">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Scan Barcode / QR untuk SKU</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-close-sku-scanner">
                                    Tutup
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="qr-reader-sku" style="max-width: 320px;"></div>
                                <small class="text-muted d-block mt-2">
                                    Setelah kode terbaca, nilai akan otomatis masuk ke kolom SKU.
                                </small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('id_kategori') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Supplier Default</label>
                            <select name="id_supplier_default" class="form-select">
                                <option value="">-- Tidak ada --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('id_supplier_default') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0"
                                value="{{ old('stok', 0) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Beli Terakhir</label>
                            <input type="text" name="harga_beli_terakhir" id="harga_beli_terakhir"
                                class="form-control rupiah-input" value="{{ old('harga_beli_terakhir') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga Jual</label>
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control rupiah-input"
                                value="{{ old('harga_jual') }}" required>
                        </div>


                        <div class="mt-3">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- JS khusus scanner SKU --}}
    <script src="{{ asset('js/product_sku_scan.js') }}"></script>
@endsection
