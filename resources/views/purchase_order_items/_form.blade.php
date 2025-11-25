@php
$qty = old('qty', $poi->qty ?? '');
$hargaBeli = old('harga_beli', $poi->harga_beli ?? '');
$idProduk = old('id_produk', $poi->id_produk ?? '');
$idPo = old('id_po', $poi->id_po ?? '');
@endphp

{{-- PRODUK --}}
<div class="mb-3">
    <label class="form-label">Produk</label>
    <select name="id_produk" required class="form-select">
        <option value="">-- Pilih Produk --</option>
        @foreach($products as $p)
            <option value="{{ $p->id_produk }}"
                {{ $idProduk == $p->id_produk ? 'selected' : '' }}>
                {{ $p->nama_produk }}
            </option>
        @endforeach
    </select>
</div>

{{-- PURCHASE ORDER --}}
<div class="mb-3">
    <label class="form-label">Nomor PO</label>
    <select name="id_po" required class="form-select">
        <option value="">-- Pilih PO --</option>
        @foreach($purchaseOrders as $po)
            <option value="{{ $po->id }}"
                {{ $idPo == $po->id ? 'selected' : '' }}>
                {{ $po->kode_po }}
            </option>
        @endforeach
    </select>
</div>

{{-- QTY --}}
<div class="mb-3">
    <label class="form-label">Qty</label>
    <input type="number" name="qty" class="form-control"
           value="{{ $qty }}" required>
</div>

{{-- HARGA BELI --}}
<div class="mb-3">
    <label class="form-label">Harga Beli</label>
    <input type="number" name="harga_beli" class="form-control"
           value="{{ $hargaBeli }}" required>
</div>
