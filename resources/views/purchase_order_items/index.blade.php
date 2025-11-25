@extends('layouts.sidebar')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase_orders_item.css') }}">

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Purchase Order Items</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Purchase</a></li>
                        <li class="breadcrumb-item active">Items</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                {{-- KIRI: FILTER + SEARCH --}}
                <div class="SecL">
                    <button type="button" class="filterBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>

                    <div class="searchWrap">
                        <span class="searchIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="myiconG">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="poi-search" class="searchInput" placeholder="Search...">
                    </div>
                </div>

                {{-- KANAN: ADD ITEM --}}
                <button type="button" id="btnOpenCreatePOI" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add Item
                </button>
            </div>



            {{-- TABLE --}}
            <div class="card border-0 shadow-sm">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card-body table-responsive p-0">
                    <table class="table mb-0 align-middle table-modern" id="poi-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Kode PO</th>
                                <th>Qty</th>
                                <th>Beli</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($items as $index => $item)
                                <tr class="poi-row" data-id="{{ $item->id_po_item }}" data-id_po="{{ $item->id_po }}"
                                    data-id_produk="{{ $item->id_produk }}" data-qty="{{ $item->qty }}"
                                    data-harga_beli="{{ number_format($item->harga_beli, 0, '', '') }}"
                                    data-subtotal="{{ $item->subtotal }}"
                                    data-delete-url="{{ route('purchase_order_items.destroy', $item->id_po_item) }}"
                                    oncontextmenu="openPOIContext(event, this)" onclick="openPOIContext(event, this)">
                                    <td>{{ $items->firstItem() + $index }}</td>

                                    <td>{{ $item->product->nama_produk ?? '-' }}</td>

                                    <td>
                                        <span class="po-code-badge">
                                            {{ $item->purchaseOrder->kode_po ?? '-' }}
                                        </span>
                                    </td>

                                    <td>{{ $item->qty }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>

                                </tr>
                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Belum ada purchase order items.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="paginationBar">
                    <div class="leftInfo">
                        {{ $items->firstItem() }} -
                        {{ $items->lastItem() }} of
                        {{ $items->total() }}
                    </div>

                    <div class="rightInfo">
                        <div class="d-flex align-items-center gap-2">
                            <span>Rows per page:</span>
                            <select id="per-page-select-poi" class="rowsSelect">
                                @foreach ([3, 6, 9, 10] as $size)
                                    <option value="{{ $size }}"
                                        {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <span class="pageInfo">
                            {{ $items->currentPage() }}/{{ $items->lastPage() }}
                        </span>

                        <div class="pageArrows">
                            @if ($items->onFirstPage())
                                <button class="pageBtn disabledBtn">&lt;</button>
                            @else
                                <a href="{{ $items->appends(['per_page' => $perPage])->previousPageUrl() }}"
                                    class="pageBtn">&lt;</a>
                            @endif

                            @if ($items->hasMorePages())
                                <a href="{{ $items->appends(['per_page' => $perPage])->nextPageUrl() }}"
                                    class="pageBtn">&gt;</a>
                            @else
                                <button class="pageBtn disabledBtn">&gt;</button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- CONTEXT MENU --}}
    <div id="poiContextMenu" class="context-menu">
        <button type="button" class="context-item" id="poi-context-edit">
            <i class="bi bi-pencil-square me-2"></i> Edit
        </button>

        <button type="button" class="context-item text-danger" id="poi-context-delete">
            <i class="bi bi-trash3 me-2"></i> Delete
        </button>
    </div>

    <form id="context-delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>


    {{-- MODAL CREATE --}}
    <div id="modalCreatePOI" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Item</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>

                <div class="card-body">
                    <form action="{{ route('purchase_order_items.store') }}" method="POST"
                        class="prevent-multi-submit">
                        @csrf

                        @php($poi = null)
                        @include('purchase_order_items._form')

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
    <div id="modalEditPOI" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Item</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>

                <div class="card-body">
                    <form id="formEditPOI" method="POST" class="prevent-multi-submit">
                        @csrf
                        @method('PUT')

                        @php($poi = null)
                        @include('purchase_order_items._form')

                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/purchase_order_items.js') }}"></script>
@endsection
