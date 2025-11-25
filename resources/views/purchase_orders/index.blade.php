@extends('layouts.sidebar')

@section('content')
    <!-- CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase_orders.css') }}">

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER / BREADCRUMB --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Purchase Orders</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Purchase</a></li>
                        <li class="breadcrumb-item" aria-current="page">Orders</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                {{-- KIRI: ICON + SEARCH --}}
                <div class="SecL">
                    <button type="button" class="filterBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>

                    {{-- SEARCH --}}
                    <div class="searchWrap">
                        <span class="searchIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="myiconG">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="po-search" class="searchInput" placeholder="Search...">
                    </div>
                </div>

                {{-- KANAN: ADD PO --}}
                <button type="button" id="btnOpenCreatePO" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add PO
                </button>
            </div>

            <div class="container-fluid">

                {{-- ALERT SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif


                {{-- TABEL PURCHASE ORDER --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0 align-middle table-modern" id="po-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Kode PO</th>
                                    <th>Supplier</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($purchaseOrders as $index => $po)
                                    <tr class="po-row" data-id="{{ $po->id }}" data-kode_po="{{ $po->kode_po }}"
                                        data-id_supplier="{{ $po->id_supplier }}" data-tanggal="{{ $po->tanggal }}"
                                        data-total="{{ (int) $po->total }}" data-status="{{ strtolower($po->status) }}"
                                        data-delete-url="{{ route('purchase_orders.destroy', $po->id) }}"
                                        oncontextmenu="openPOContext(event, this)" onclick="openPOContext(event, this)">

                                        <td>{{ $purchaseOrders->firstItem() + $index }}</td>

                                        {{-- BADGE KODE PO --}}
                                        <td>
                                            <a href="#" class="po-code-badge">
                                                {{ $po->kode_po }}
                                            </a>
                                        </td>

                                        <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>

                                        <td>{{ \Carbon\Carbon::parse($po->tanggal)->format('d-m-Y') }}</td>

                                        <td>Rp {{ number_format($po->total, 0, ',', '.') }}</td>

                                        {{-- STATUS PILL --}}
                                        @php
                                            $status = strtolower($po->status);
                                            $statusClass = match ($status) {
                                                'purchase' => 'status-pending',
                                                'selesai' => 'status-done',
                                                'draft' => 'status-other',
                                                default => 'status-other',
                                            };
                                        @endphp

                                        <td>
                                            <span class="status-pill {{ $statusClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Belum ada purchase order.</td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>


                    {{-- PAGINATION --}}
                    <div class="paginationBar">
                        <div class="leftInfo">
                            {{ $purchaseOrders->firstItem() }}–{{ $purchaseOrders->lastItem() }}
                            of {{ $purchaseOrders->total() }}
                        </div>

                        <div class="rightInfo">
                            <div class="d-flex align-items-center gap-2">
                                <span>Rows per page:</span>
                                <select id="per-page-select-po" class="rowsSelect">
                                    @foreach ([3, 6, 9] as $size)
                                        <option value="{{ $size }}"
                                            {{ request('per_page', 3) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <span class="pageInfo">
                                {{ $purchaseOrders->currentPage() }}/{{ $purchaseOrders->lastPage() }}
                            </span>

                            <div class="pageArrows">

                                {{-- PREV --}}
                                @if ($purchaseOrders->onFirstPage())
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ $purchaseOrders->appends(['per_page' => $perPage])->previousPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </a>
                                @endif

                                {{-- NEXT --}}
                                @if ($purchaseOrders->hasMorePages())
                                    <a href="{{ $purchaseOrders->appends(['per_page' => $perPage])->nextPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                @else
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </button>
                                @endif


                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- CONTEXT MENU --}}
    <div id="poContextMenu" class="context-menu">
        <button type="button" class="context-item" id="po-context-edit">
            <i class="bi bi-pencil-square me-2"></i> Edit
        </button>

        <button type="button" class="context-item text-danger" id="po-context-delete">
            <i class="bi bi-trash3 me-2"></i> Delete
        </button>
    </div>

    <form id="context-delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>


    {{-- MODAL CREATE --}}
    <div id="modalCreatePO" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Purchase Order</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form action="{{ route('purchase_orders.store') }}" method="POST" class="prevent-multi-submit">
                        @csrf
                        @php($purchaseOrder = null)
                        @include('purchase_orders._form')

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
    <div id="modalEditPO" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Purchase Order</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form id="formEditPO" method="POST" class="prevent-multi-submit">
                        @csrf
                        @method('PUT')

                        @php($purchaseOrder = null)
                        @include('purchase_orders._form')

                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/purchase_orders.js') }}"></script>
@endsection
