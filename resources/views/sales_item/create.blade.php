@extends('layouts.sidebar')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Tambah Sales Item</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('sales_item') }}">Sales Item</a></li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">

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

                        <div class="mt-3">
                            <a href="{{ url('sales_item') }}" class="btn btn-secondary">Kembali</a>
                            <button type="button" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
