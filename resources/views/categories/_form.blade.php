<div class="mb-3">
    <label class="form-label">Nama Kategori</label>
    <input
        type="text"
        name="nama"
        class="form-control"
        value="{{ old('nama', $category->nama ?? '') }}"
        required
    >
</div>

<div class="mb-3">
    <label class="form-label">Keterangan</label>
    <textarea
        name="keterangan"
        class="form-control"
        rows="3"
        required
    >{{ old('keterangan', $category->keterangan ?? '') }}</textarea>
</div>
