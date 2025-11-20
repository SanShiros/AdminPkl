@extends('layouts.sidebar')

@section('content')
    <!-- Panggil CSS eksternal -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}"><!-- bisa copy dari suppliers.css dulu -->

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER / BREADCRUMB --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Daftar Kategori</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Categories</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                {{-- KIRI: ICON + SEARCH --}}
                <div class="SecL">
                    {{-- icon filter --}}
                    <button type="button" class="filterBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-filter">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </button>

                    {{-- search --}}
                    <div class="searchWrap">
                        <span class="searchIcon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="myiconG">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" id="category-search" class="searchInput" placeholder="Search...">
                    </div>
                </div>

                {{-- KANAN: ADD CATEGORY (BUKA MODAL) --}}
                <button type="button" id="btnOpenCreateCategory" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add category
                </button>
            </div>

            <div class="container-fluid">
                {{-- ALERT SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABEL CATEGORY --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0 align-middle table-modern" id="category-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px">#</th>
                                    <th>Nama Kategori</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $k)
                                    <tr class="category-row" data-id="{{ $k->id }}" data-nama="{{ $k->nama }}"
                                        data-keterangan="{{ $k->keterangan }}"
                                        data-delete-url="{{ route('categories.destroy', $k->id) }}"
                                        oncontextmenu="openCategoryContext(event, this)"
                                        onclick="openCategoryContext(event, this)">
                                        <td>{{ $categories->firstItem() + $loop->index }}</td>
                                        <td>{{ $k->nama }}</td>
                                        <td>{{ $k->keterangan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="paginationBar">
                        {{-- KIRI: INFO JUMLAH DATA --}}
                        <div class="leftInfo">
                            {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ $categories->total() }}
                        </div>

                        {{-- KANAN: ROWS PER PAGE + PAGE + ARROW --}}
                        <div class="rightInfo">
                            <div class="d-flex align-items-center gap-2">
                                <span>Rows per page:</span>
                                <select id="category-per-page-select" class="rowsSelect">
                                    @foreach ([3, 6, 9] as $size)
                                        <option value="{{ $size }}"
                                            {{ request('per_page', $perPage) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <span class="pageInfo">
                                {{ $categories->currentPage() }}/{{ $categories->lastPage() }}
                            </span>

                            <div class="pageArrows">
                                {{-- PREV --}}
                                @if ($categories->onFirstPage())
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-left">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ $categories->appends(['per_page' => $perPage])->previousPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-left">
                                            <line x1="19" y1="12" x2="5" y2="12"></line>
                                            <polyline points="12 19 5 12 12 5"></polyline>
                                        </svg>
                                    </a>
                                @endif

                                {{-- NEXT --}}
                                @if ($categories->hasMorePages())
                                    <a href="{{ $categories->appends(['per_page' => $perPage])->nextPageUrl() }}"
                                        class="pageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-right">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                @else
                                    <button class="pageBtn disabledBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-right">
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

  <div id="categoryContextMenu" class="context-menu">
    <button type="button" class="context-item" id="category-context-edit">
        <i class="bi bi-pencil-square me-2"></i> Edit
    </button>
    <button type="button" class="context-item text-danger" id="category-context-delete">
        <i class="bi bi-trash3 me-2"></i> Delete
    </button>
</div>


    {{-- MODAL CREATE CATEGORY --}}
    <div id="modalCreateCategory" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" class="prevent-multi-submit">
                        @csrf
                        @php($category = null)
                        @include('categories._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT CATEGORY --}}
    <div id="modalEditCategory" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-close-modal>&times;</button>
                </div>
                <div class="card-body">
                    <form id="formEditCategory" method="POST" class="prevent-multi-submit">
                        @csrf
                        @method('PUT')
                        @php($category = null)
                        @include('categories._form')
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Panggil JS eksternal -->
    <script src="{{ asset('js/categories.js') }}"></script>
@endsection
