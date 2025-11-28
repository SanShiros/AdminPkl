@extends('layouts.sidebar')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales.css') }}">

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Daftar Sales</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Sales</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                <div class="SecL">
                    <button type="button" class="filterBtn">...</button>

                    <div class="searchWrap">
                        <span class="searchIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="myiconG">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="sale-search" class="searchInput"
                            placeholder="Search kode nota atau metode...">
                    </div>
                </div>

              
                <a href="{{ route('sales.create') }}" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Sales
                </a>

            </div>

            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0 align-middle table-modern" id="sale-table">
                            <thead>
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Kode Nota</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Bayar</th>
                                    <th>Kembalian</th>
                                    <th>Metode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $index => $sale)
                                    <tr class="sale-row" data-id="{{ $sale->id_sales }}"
                                        data-kode_nota="{{ $sale->kode_nota }}" data-tanggal="{{ $sale->tanggal }}"
                                        data-total="{{ $sale->total }}" data-bayar="{{ $sale->bayar }}"
                                        data-kembalian="{{ $sale->kembalian }}"
                                        data-metode_bayar="{{ $sale->metode_bayar }}"
                                        data-delete-url="{{ route('sales.destroy', ['sale' => $sale->id_sale]) }}"
                                        oncontextmenu="openSaleContext(event, this)" onclick="openSaleContext(event, this)">
                                        <td>{{ $sales->firstItem() + $index }}</td>
                                        <td>{{ $sale->kode_nota }}</td>
                                        <td>{{ \Carbon\Carbon::parse($sale->tanggal)->format('Y-m-d H:i') }}</td>
                                        <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($sale->bayar, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($sale->kembalian, 0, ',', '.') }}</td>
                                        <td>{{ $sale->metode_bayar }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Belum ada data sales.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="paginationBar">
                        <div class="leftInfo">
                            @if ($sales->total() > 0)
                                {{ $sales->firstItem() }}–{{ $sales->lastItem() }} of {{ $sales->total() }}
                            @else
                                0 of 0
                            @endif
                        </div>

                        <div class="rightInfo">
                            <div class="d-flex align-items-center gap-2">
                                <span>Rows per page:</span>
                                <select id="per-page-select-sales" class="rowsSelect">
                                    @foreach ([3, 6, 9] as $size)
                                        <option value="{{ $size }}"
                                            {{ request('per_page', 6) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <span class="pageInfo">{{ $sales->currentPage() }}/{{ $sales->lastPage() }}</span>

                            <div class="pageArrows">
                                {{-- prev --}}
                                @if ($sales->onFirstPage())
                                    <button class="pageBtn disabledBtn">◀</button>
                                @else
                                    <a href="{{ $sales->appends(['per_page' => $perPage])->previousPageUrl() }}"
                                        class="pageBtn">◀</a>
                                @endif

                                {{-- next --}}
                                @if ($sales->hasMorePages())
                                    <a href="{{ $sales->appends(['per_page' => $perPage])->nextPageUrl() }}"
                                        class="pageBtn">▶</a>
                                @else
                                    <button class="pageBtn disabledBtn">▶</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> {{-- card --}}
            </div> {{-- container --}}
        </div>
    </div>

    {{-- CONTEXT MENU --}}
    <div id="saleContextMenu" class="context-menu">
        <button type="button" class="context-item" id="sale-context-edit"><i class="bi bi-pencil-square me-2"></i>
            Edit</button>
        <button type="button" class="context-item text-danger" id="sale-context-delete"><i class="bi bi-trash3 me-2"></i>
            Delete</button>
    </div>

    <form id="context-delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- MODAL CREATE --}}
    <div id="modalCreateSale" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Sale</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form action="{{ route('sales.store') }}" method="POST" class="prevent-multi-submit">
                        @csrf
                        @php($sale = null)
                        @include('sales._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditSale" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Sale</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form id="formEditSale" method="POST" class="prevent-multi-submit">
                        @csrf
                        @method('PUT')
                        @php($sale = null)
                        @include('sales._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/sales.js') }}"></script>
@endsection
