<div class="row g-2">
    <div class="col-md-6">
        <div class="mb-2">
            <label for="kode_nota" class="form-label">Kode Nota</label>
            <input type="text" name="kode_nota" id="kode_nota" class="form-control" value="{{ old('kode_nota', $sale->kode_nota ?? '') }}" required>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-2">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="datetime-local" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', isset($sale->tanggal) ? \Carbon\Carbon::parse($sale->tanggal)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-2">
            <label for="total" class="form-label">Total</label>
            <input type="number" step="0.01" name="total" id="total" class="form-control" value="{{ old('total', $sale->total ?? 0) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-2">
            <label for="bayar" class="form-label">Bayar</label>
            <input type="number" step="0.01" name="bayar" id="bayar" class="form-control" value="{{ old('bayar', $sale->bayar ?? 0) }}" required>
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-2">
            <label for="kembalian" class="form-label">Kembalian</label>
            <input type="number" step="0.01" name="kembalian" id="kembalian" class="form-control" value="{{ old('kembalian', $sale->kembalian ?? 0) }}" readonly>
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-2">
            <label for="metode_bayar" class="form-label">Metode Bayar</label>
            <input type="text" name="metode_bayar" id="metode_bayar" class="form-control" value="{{ old('metode_bayar', $sale->metode_bayar ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-2">
            <label for="id_user" class="form-label">User</label>
            <select name="id_user" id="id_user" class="form-control">
                <option value="">-</option>
                @foreach(\App\Models\User::limit(50)->get() as $u)
                    <option value="{{ $u->id }}" {{ (old('id_user', $sale->id_user ?? '') == $u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<script>
    // hitung kembalian live
    document.addEventListener('input', function (e) {
        const total = parseFloat(document.getElementById('total')?.value || 0);
        const bayar = parseFloat(document.getElementById('bayar')?.value || 0);
        const kembalian = Math.max(0, bayar - total);
        if (document.getElementById('kembalian')) {
            document.getElementById('kembalian').value = kembalian.toFixed(2);
        }
    });
</script>
