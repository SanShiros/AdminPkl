<div class="mb-3">
    <label class="form-label">Nama Supplier</label>
    <input type="text" name="nama_supplier" class="form-control @error('nama_supplier') is-invalid @enderror"
        value="{{ old('nama_supplier', $supplier->nama_supplier ?? '') }}" required>
    @error('nama_supplier')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Telepon</label>
    <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
        value="{{ old('telepon', $supplier->telepon ?? '') }}">
    @error('telepon')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $supplier->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $supplier->alamat ?? '') }}</textarea>
    @error('alamat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
