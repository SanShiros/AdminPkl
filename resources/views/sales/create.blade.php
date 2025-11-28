@extends('layouts.sidebar')

@section('content')
    <!-- CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales.css') }}">
    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER / BREADCRUMB --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Tambah Sales (POS)</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Transaksi</a></li>
                        <li class="breadcrumb-item" aria-current="page">Tambah Sales</li>
                    </ul>
                </div>
            </div>

            {{-- KONTEN --}}
            <div class="container-fluid">

                {{-- ALERT ERROR / SUCCESS --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('sales.store') }}" method="POST" id="sales-pos-form" class="prevent-multi-submit">
                    @csrf

                    {{-- CARD: INFO TRANSAKSI --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Informasi Transaksi</h6>
                            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">
                                Kembali ke daftar
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- KODE NOTA --}}
                                <div class="col-md-4">
                                    <label class="form-label">Kode Nota</label>
                                    <input type="text" name="kode_nota"
                                        class="form-control @error('kode_nota') is-invalid @enderror"
                                        value="{{ old('kode_nota', $defaultKodeNota ?? '') }}" required>
                                    @error('kode_nota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- TANGGAL --}}
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal</label>
                                    <input type="datetime-local" name="tanggal"
                                        class="form-control @error('tanggal') is-invalid @enderror"
                                        value="{{ old('tanggal', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- KASIR --}}
                                <div class="col-md-4">
                                    <label class="form-label">Kasir</label>
                                    <select name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                                        <option value="">-- Pilih Kasir --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name ?? ($user->nama ?? 'User ' . $user->id) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD: KERANJANG --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Keranjang</h6>
                            <div class="d-flex align-items-center gap-2">
                                <select id="product-select" class="form-select form-select-sm" style="min-width: 260px;">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id_produk }}" data-harga="{{ $product->harga_jual }}"
                                            data-nama="{{ $product->nama_produk }}">
                                            {{ $product->nama_produk }}
                                            (SKU: {{ $product->sku ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="number" id="qty-input" class="form-control form-control-sm" placeholder="Qty"
                                    min="1" value="1" style="width: 80px;">

                                <button type="button" id="btn-add-item" class="btn btn-sm btn-primary">
                                    Tambah
                                </button>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table mb-0 align-middle table-modern" id="cart-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Produk</th>
                                        <th style="width: 90px;">Qty</th>
                                        <th style="width: 140px;">Harga</th>
                                        <th style="width: 160px;">Subtotal</th>
                                        <th style="width: 80px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Diisi oleh JS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- CARD: TOTAL & PEMBAYARAN --}}
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Total</label>
                                    <input type="text" id="total-display" class="form-control" value="Rp 0" readonly>
                                    <input type="hidden" name="total" id="total-input" value="0">
                                    @error('total')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Bayar</label>
                                    <input type="text" name="bayar" id="bayar-input"
                                        class="form-control rupiah-input @error('bayar') is-invalid @enderror"
                                        value="{{ old('bayar', 0) }}">
                                    @error('bayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-3">
                                    <label class="form-label">Kembalian</label>

                                    {{-- Hanya untuk tampilan (Rp xx) --}}
                                    <input type="text" id="kembalian-display" class="form-control" value="Rp 0"
                                        readonly>

                                    {{-- Angka murni yang dikirim ke backend --}}
                                    <input type="hidden" name="kembalian" id="kembalian-input"
                                        value="{{ old('kembalian', 0) }}">

                                    @error('kembalian')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Metode Bayar</label>
                                    <select name="metode_bayar"
                                        class="form-select @error('metode_bayar') is-invalid @enderror">
                                        <option value="Cash" {{ old('metode_bayar') == 'Cash' ? 'selected' : '' }}>Cash
                                        </option>
                                        <option value="Transfer"
                                            {{ old('metode_bayar') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="QRIS" {{ old('metode_bayar') == 'QRIS' ? 'selected' : '' }}>QRIS
                                        </option>
                                    </select>
                                    @error('metode_bayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- HIDDEN KERANJANG --}}
                    <input type="hidden" name="keranjang" id="keranjang-input">

                    <div class="d-flex justify-content-end gap-2 mb-4">
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div> {{-- .container-fluid --}}
        </div> {{-- .pc-content --}}
    </div> {{-- .pc-container --}}

    {{-- JS POS --}}
    <script src="{{ asset('js/sales_pos.js') }}"></script>
@endsection
