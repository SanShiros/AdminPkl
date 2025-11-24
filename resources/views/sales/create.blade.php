@extends('layouts.sidebar')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="page-header-title">
                        <h5 class="mb-0 font-medium">Tambah Sales</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
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
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Kode Nota</label>
                            <input type="text" name="kode_nota" class="form-control"
                                   value="{{ old('kode_nota') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="datetime-local" name="tanggal" class="form-control"
                                   value="{{ old('tanggal') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <input type="number" step="0.01" name="total" class="form-control"
                                   value="{{ old('total') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bayar</label>
                            <input type="number" step="0.01" name="bayar" class="form-control"
                                   value="{{ old('bayar') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kembalian (otomatis bila kosong)</label>
                            <input type="number" step="0.01" name="kembalian" class="form-control"
                                   value="{{ old('kembalian') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-select">
                                <option value="">-- Pilih --</option>
                                <option value="cash" {{ old('metode_bayar') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="transfer" {{ old('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="qris" {{ old('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kasir (User)</label>
                            <select name="id_user" class="form-select">
                                <option value="">-- Pilih Kasir --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
