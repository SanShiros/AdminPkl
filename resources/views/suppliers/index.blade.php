@extends('layouts.sidebar')
@section('content')
  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="page-header-title">
            <h5 class="mb-0 font-medium">Typography</h5>
          </div>
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard/index.html">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript: void(0)">Suppliers</a></li>
            <li class="breadcrumb-item" aria-current="page">Supplier</li>
          </ul>
        </div>
      </div>
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Daftar Supplier</h4>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                + Tambah Supplier
            </a>
        </div>
    
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Supplier</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $index => $supplier)
                            <tr>
                                <td>{{ $suppliers->firstItem() + $index }}</td>
                                <td>{{ $supplier->nama_supplier }}</td>
                                <td>{{ $supplier->telepon ?? '-' }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>{{ $supplier->alamat ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
    
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

