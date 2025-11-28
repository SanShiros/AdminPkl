@extends('layouts.sidebar')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Riwayat Pergerakan Stok</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Stok</a></li>
                        <li class="breadcrumb-item" aria-current="page">Stock Movements</li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form class="row g-3" method="GET" action="{{ route('stock_movements.index') }}">
                        <div class="col-md-4">
                            <label class="form-label">Produk</label>
                            <select name="id_produk" class="form-select">
                                <option value="">-- Semua Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id_produk }}"
                                        {{ request('id_produk') == $product->id_produk ? 'selected' : '' }}>
                                        {{ $product->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tipe</label>
                            <select name="tipe" class="form-select">
                                <option value="">-- Semua --</option>
                                <option value="IN"  {{ request('tipe') == 'IN' ? 'selected' : '' }}>IN (Masuk)</option>
                                <option value="OUT" {{ request('tipe') == 'OUT' ? 'selected' : '' }}>OUT (Keluar)</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Sumber</label>
                            <select name="sumber" class="form-select">
                                <option value="">-- Semua --</option>
                                <option value="PURCHASE"        {{ request('sumber') == 'PURCHASE' ? 'selected' : '' }}>PURCHASE</option>
                                <option value="PURCHASE_ADJUST" {{ request('sumber') == 'PURCHASE_ADJUST' ? 'selected' : '' }}>PURCHASE_ADJUST</option>
                                <option value="PURCHASE_DELETE" {{ request('sumber') == 'PURCHASE_DELETE' ? 'selected' : '' }}>PURCHASE_DELETE</option>
                                <option value="SALE"            {{ request('sumber') == 'SALE' ? 'selected' : '' }}>SALE</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body table-responsive p-0">
                    <table class="table mb-0 align-middle table-modern">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Tipe</th>
                                <th>Qty</th>
                                <th>Sumber</th>
                                <th>ID Ref</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $mv)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($mv->tanggal)->format('Y-m-d H:i') }}</td>
                                    <td>{{ $mv->product->nama_produk ?? ('#'.$mv->id_produk) }}</td>
                                    <td>{{ $mv->tipe }}</td>
                                    <td>{{ $mv->qty }}</td>
                                    <td>{{ $mv->sumber }}</td>
                                    <td>{{ $mv->id_ref }}</td>
                                    <td>{{ $mv->keterangan }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Belum ada data pergerakan stok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="paginationBar p-3">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
