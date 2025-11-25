@php
    // nilai yang disimpan di DB (lowercase)
    $statusOptions = [
        'draft'    => 'Draft',
        'purchase' => 'Purchase',
        'selesai'  => 'Selesai',
    ];

    /** @var \App\Models\PurchaseOrder|null $purchaseOrder */
    $currentStatus = old(
        'status',
        isset($purchaseOrder) && $purchaseOrder->status
            ? strtolower($purchaseOrder->status)
            : 'draft'
    );
@endphp

{{-- KODE PO --}}
<div class="mb-3">
    <label class="form-label">Kode PO</label>
    <input
        type="text"
        name="kode_po"
        class="form-control @error('kode_po') is-invalid @enderror"
        value="{{ old('kode_po', $purchaseOrder->kode_po ?? '') }}"
        required
    >
    @error('kode_po')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- STATUS --}}
<div class="mb-3">
    <label class="form-label">Status</label>
    <select
        name="status"
        class="form-select @error('status') is-invalid @enderror"
        required
    >
        @foreach ($statusOptions as $value => $label)
            <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- SUPPLIER --}}
<div class="mb-3">
    <label class="form-label">Supplier</label>
    <select
        name="id_supplier"
        class="form-select @error('id_supplier') is-invalid @enderror"
        required
    >
        <option value="">-- Pilih Supplier --</option>
        @foreach ($suppliers as $supplier)
            <option
                value="{{ $supplier->id }}"
                {{ old('id_supplier', $purchaseOrder->id_supplier ?? '') == $supplier->id ? 'selected' : '' }}
            >
                {{ $supplier->nama_supplier }}
            </option>
        @endforeach
    </select>
    @error('id_supplier')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- TANGGAL --}}
<div class="mb-3">
    <label class="form-label">Tanggal</label>
    <input
        type="date"
        name="tanggal"
        class="form-control @error('tanggal') is-invalid @enderror"
        value="{{ old('tanggal', $purchaseOrder->tanggal ?? '') }}"
        required
    >
    @error('tanggal')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- TOTAL --}}
<div class="mb-3">
    <label class="form-label">Total</label>
    <input
        type="text" {{-- boleh number, tapi text enak buat format rupiah --}}
        name="total"
        class="form-control @error('total') is-invalid @enderror"
        value="{{ old('total', $purchaseOrder->total ?? 0) }}"
        required
    >
    @error('total')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
