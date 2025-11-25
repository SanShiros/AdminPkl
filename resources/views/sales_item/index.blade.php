@extends('layouts.sidebar')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">

    <div class="pc-container">
        <div class="pc-content">

            {{-- HEADER --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Daftar Sales Item</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Sales Item</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
            </div>

            <div class="footSup">
                <div class="SecL">
                    <button type="button" class="filterBtn">...</button>

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
                        <input type="text" id="sale-search" class="searchInput" placeholder="Search kode nota atau metode...">
                    </div>
                </div>

                <button type="button" id="btnOpenCreateSaleItem" class="btnAddCustomer">
                    <i class="bi bi-plus-lg me-1"></i> Add Sales Item
                </button>
            </div>

            <div class="container-fluid">

                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0 align-middle table-modern" id="sale-table">
                            <thead>
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Kode Nota</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data Dummy --}}
                                <tr onclick="openEditSalesItem()">
                                    <td>1</td>
                                    <td>MJ250121-015</td>
                                    <td>Sabun Lifebuoy 100g</td>
                                    <td>2</td>
                                    <td>Rp 7.000</td>
                                    <td>Rp 14.000</td>
                                </tr>

                                <tr onclick="openEditSalesItem()">
                                    <td>2</td>
                                    <td>MJ250121-015</td>
                                    <td>Teh Pucuk 350ml</td>
                                    <td>1</td>
                                    <td>Rp 5.000</td>
                                    <td>Rp 5.000</td>
                                </tr>

                                <tr onclick="openEditSalesItem()">
                                    <td>3</td>
                                    <td>MJ250121-016</td>
                                    <td>Beras 5kg</td>
                                    <td>1</td>
                                    <td>Rp 59.000</td>
                                    <td>Rp 59.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- POPUP CREATE --}}
    <div id="modalCreateSalesItem" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Sales Item</h5>
                    <button type="button" class="btn-close" onclick="closeCreateSalesItem()">&times;</button>
                </div>
                <div class="card-body">

                    {{-- FORM MANUAL, TANPA ROUTE --}}
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Kode Nota</label>
                            <input type="text" class="form-control" placeholder="Masukkan kode nota">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" placeholder="Nama barang">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Qty</label>
                            <input type="number" class="form-control" placeholder="Jumlah">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" placeholder="Harga per item">
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-secondary" onclick="closeCreateSalesItem()">Batal</button>
                            <button type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- POPUP EDIT --}}
    <div id="modalEditSalesItem" class="custom-modal-backdrop d-none">
        <div class="custom-modal-dialog">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Sales Item</h5>
                    <button type="button" class="btn-close" onclick="closeEditSalesItem()">&times;</button>
                </div>

                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Kode Nota</label>
                            <input type="text" class="form-control" value="MJ250121-015">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" value="Sabun Lifebuoy 100g">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Qty</label>
                            <input type="number" class="form-control" value="2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" value="7000">
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-secondary" onclick="closeEditSalesItem()">Batal</button>
                            <button type="button" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function openCreateSalesItem() {
            document.getElementById('modalCreateSalesItem').classList.remove('d-none');
        }

        function closeCreateSalesItem() {
            document.getElementById('modalCreateSalesItem').classList.add('d-none');
        }

        function openEditSalesItem() {
            document.getElementById('modalEditSalesItem').classList.remove('d-none');
        }

        function closeEditSalesItem() {
            document.getElementById('modalEditSalesItem').classList.add('d-none');
        }

        document.getElementById('btnOpenCreateSaleItem').onclick = openCreateSalesItem;
    </script>

@endsection
