@extends('layouts.sidebar')

@section('content')
    <!-- CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}"><!-- atau pakai suppliers.css dulu -->

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER / BREADCRUMB --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Daftar Produk</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Products</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                {{-- KIRI: ICON + SEARCH --}}
                <div class="SecL">
                    {{-- icon filter (opsional) --}}
                    <button type="button" class="filterBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>

                    {{-- search --}}
                    <div class="searchWrap">
                        <span class="searchIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="myiconG">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="product-search" class="searchInput" placeholder="Search...">
                    </div>
                </div>

                {{-- KANAN: ADD PRODUCT (BUKA MODAL) --}}
                <button type="button" id="btnOpenCreateProduct" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add product
                </button>
            </div>

            <div class="container-fluid">
                {{-- ALERT SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABEL PRODUCT --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0 align-middle table-modern" id="product-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Stok</th>
                                    <th>Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                    <tr class="product-row"
                                        data-id="{{ $product->id_produk }}"
                                        data-nama_produk="{{ $product->nama_produk }}"
                                        data-sku="{{ $product->sku }}"
                                        data-id_kategori="{{ $product->id_kategori }}"
                                        data-id_supplier_default="{{ $product->id_supplier_default }}"
                                        data-stok="{{ $product->stok }}"
                                        data-harga_beli_terakhir="{{ $product->harga_beli_terakhir }}"
                                        data-harga_jual="{{ $product->harga_jual }}"
                                        data-delete-url="{{ route('products.destroy', $product->id_produk) }}"
                                        oncontextmenu="openProductContext(event, this)"
                                        onclick="openProductContext(event, this)">
                                        <td>{{ $products->firstItem() + $index }}</td>
                                        <td>{{ $product->nama_produk }}</td>
                                        <td>{{ $product->sku ?? '-' }}</td>
                                        <td>{{ $product->category->nama ?? '-' }}</td>
                                        <td>{{ $product->supplier->nama_supplier ?? '-' }}</td>
                                        <td>{{ $product->stok }}</td>
                                        <td>Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Belum ada data produk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="paginationBar">
                        {{-- KIRI: INFO JUMLAH DATA --}}
                        <div class="leftInfo">
                            {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                        </div>

                        {{-- KANAN: ROWS PER PAGE + PAGE + ARROW --}}
                        <div class="rightInfo">
                            <div class="d-flex align-items-center gap-2">
                                <span>Rows per page:</span>
                                <select id="per-page-select-products" class="rowsSelect">
                                    @foreach ([3, 6, 9] as $size)
                                        <option value="{{ $size }}"
                                            {{ request('per_page', 3) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <span class="pageInfo">
                                {{ $products->currentPage() }}/{{ $products->lastPage() }}
                            </span>

                            <div class="pageArrows">
                                {{-- PREV --}}
                                @if ($products->onFirstPage())
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ $products->appends(['per_page' => $perPage])->previousPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </a>
                                @endif

                                {{-- NEXT --}}
                                @if ($products->hasMorePages())
                                    <a href="{{ $products->appends(['per_page' => $perPage])->nextPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                @else
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> {{-- card --}}
            </div> {{-- container-fluid --}}
        </div> {{-- pc-content --}}
    </div> {{-- pc-container --}}

    {{-- CONTEXT MENU KLIK KANAN PRODUCT --}}
    <div id="productContextMenu" class="context-menu">
        <button type="button" class="context-item" id="product-context-edit">
            <i class="bi bi-pencil-square me-2"></i> Edit
        </button>

        <button type="button" class="context-item text-danger" id="product-context-delete">
            <i class="bi bi-trash3 me-2"></i> Delete
        </button>
    </div>

    {{-- FORM DELETE GLOBAL --}}
    <form id="context-delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    {{-- MODAL CREATE PRODUCT --}}
    <div id="modalCreateProduct" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" class="prevent-multi-submit">
                        @csrf
                        @php($product = null)
                        @include('products._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PRODUCT --}}
    <div id="modalEditProduct" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Produk</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form id="formEditProduct" method="POST" class="prevent-multi-submit">
                        @csrf
                        @method('PUT')
                        @php($product = null)
                        @include('products._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JS eksternal -->
    <script src="{{ asset('js/products.js') }}"></script>
@endsection
