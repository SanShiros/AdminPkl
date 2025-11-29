document.addEventListener("DOMContentLoaded", function () {
    let cart = []; // [{id, nama, harga, qty}]

    const cartBody         = document.querySelector("#cart-table tbody");
    const productSelect    = document.querySelector("#product-select");
    const qtyInput         = document.querySelector("#qty-input");
    const btnAddItem       = document.querySelector("#btn-add-item");
    const totalDisplay     = document.querySelector("#total-display");
    const totalInput       = document.querySelector("#total-input");
    const keranjangInput   = document.querySelector("#keranjang-input");
    const bayarInput       = document.querySelector("#bayar-input");
    const kembalianInput   = document.querySelector("#kembalian-input");
    const kembalianDisplay = document.querySelector("#kembalian-display");
    const lastScanSkuInput = document.querySelector("#last-scan-sku");

    function formatRupiah(num) {
        num = Number(num || 0);
        return "Rp " + num.toLocaleString("id-ID");
    }

    function hitungKembalian() {
        const total = parseFloat(totalInput.value || 0);
        const bayar = parseFloat(bayarInput.value || 0);
        const kembalian = bayar - total;

        if (kembalian >= 0) {
            kembalianInput.value = kembalian;           // angka ke backend
            kembalianDisplay.value = formatRupiah(kembalian); // tampilan
        } else {
            kembalianInput.value = 0;
            kembalianDisplay.value = "";
        }
    }

    function syncHiddenKeranjang() {
        const payload = cart.map(item => ({
            id: item.id,
            qty: item.qty,
            harga_jual: item.harga
        }));
        keranjangInput.value = JSON.stringify(payload);
    }

    function renderCart() {
        cartBody.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.nama}</td>
                <td>
                    <input type="number"
                           min="1"
                           value="${item.qty}"
                           data-index="${index}"
                           class="form-control form-control-sm qty-input">
                </td>
                <td>${formatRupiah(item.harga)}</td>
                <td>${formatRupiah(subtotal)}</td>
                <td>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove"
                            data-index="${index}">
                        Hapus
                    </button>
                </td>
            `;
            cartBody.appendChild(tr);
        });

        totalDisplay.value = formatRupiah(total);
        totalInput.value = total;

        syncHiddenKeranjang();
        hitungKembalian();
    }

    function addToCart(id, nama, harga, qty = 1) {
        const exist = cart.find(item => String(item.id) === String(id));

        if (exist) {
            exist.qty += qty;
        } else {
            cart.push({
                id,
                nama,
                harga: Number(harga || 0),
                qty
            });
        }
        renderCart();
    }

    // Tambah manual
    btnAddItem.addEventListener("click", () => {
        const selected = productSelect.options[productSelect.selectedIndex];
        const id = selected.value;

        if (!id) return alert("Pilih produk dulu.");

        const harga = parseFloat(selected.dataset.harga || 0);
        const nama = selected.dataset.nama || "Produk";
        const qty  = parseInt(qtyInput.value || "1", 10);

        addToCart(id, nama, harga, qty);
    });

    // Ubah qty
    cartBody.addEventListener("input", e => {
        if (e.target.classList.contains("qty-input")) {
            const idx = e.target.getAttribute("data-index");
            let newQty = parseInt(e.target.value || "1", 10);
            if (newQty < 1 || isNaN(newQty)) newQty = 1;
            cart[idx].qty = newQty;
            renderCart();
        }
    });

    // Hapus item
    cartBody.addEventListener("click", e => {
        if (e.target.classList.contains("btn-remove")) {
            const idx = e.target.getAttribute("data-index");
            cart.splice(idx, 1);
            renderCart();
        }
    });

    // Hitung kembalian
    bayarInput.addEventListener("input", hitungKembalian);

    // =========================
    //  SCANNER (kamera)
    // =========================
    const qrContainer = document.getElementById("qr-reader");
    if (qrContainer && window.Html5QrcodeScanner) {
        const baseUrlMeta = document.querySelector('meta[name="base-url"]');
        const baseUrl = baseUrlMeta ? baseUrlMeta.getAttribute("content") : "";

        const onScanSuccess = (decodedText) => {
            const kode = (decodedText || "").trim();
            console.log("Kode terbaca:", kode);

            if (!kode) return;

            if (lastScanSkuInput) {
                lastScanSkuInput.value = kode;
            }

            fetch(`${baseUrl}/sales/product-by-sku/${encodeURIComponent(kode)}`)
                .then(async (res) => {
                    if (!res.ok) {
                        let data = {};
                        try { data = await res.json(); } catch {}
                        alert(data.message || "Produk tidak ditemukan: " + kode);
                        return null;
                    }
                    return res.json();
                })
                .then((data) => {
                    if (!data) return;
                    addToCart(data.id, data.nama, data.harga, 1);
                })
                .catch((err) => {
                    console.error(err);
                    alert("Terjadi kesalahan saat mengambil data produk.");
                });
        };

        const onScanError = (err) => {
            // boleh dikosongkan biar nggak spam error
            // console.warn(err);
        };

        // config sederhana dulu (biar QR pasti jalan, barcode bisa dicoba)
        const config = {
            fps: 15,
            qrbox: { width: 400, height: 200 }
        };

        const scanner = new Html5QrcodeScanner("qr-reader", config);
        scanner.render(onScanSuccess, onScanError);
    }

    // Init pertama
    renderCart();
});

document.addEventListener("DOMContentLoaded", function () {
    let cart = []; // [{id, nama, harga, qty}]

    const cartBody       = document.querySelector("#cart-table tbody");
    const productSelect  = document.querySelector("#product-select");
    const qtyInput       = document.querySelector("#qty-input");
    const btnAddItem     = document.querySelector("#btn-add-item");
    const totalDisplay   = document.querySelector("#total-display");
    const totalInput     = document.querySelector("#total-input");
    const keranjangInput = document.querySelector("#keranjang-input");
    const bayarInput     = document.querySelector("#bayar-input");

    // Kembalian → input hidden (angka murni) + display (Rp)
    const kembalianInput   = document.querySelector("#kembalian-input");
    const kembalianDisplay = document.querySelector("#kembalian-display");

    const form = document.querySelector("#sales-pos-form");

    // ===== UTIL =====
    function formatRupiahView(num) {
        num = Number(num || 0);
        return "Rp " + num.toLocaleString("id-ID");
    }

    // untuk input text: "20000" -> "20.000"
    function formatRupiahInput(str) {
        str = (str || "").toString();
        // hanya angka
        let cleaned = str.replace(/[^0-9]/g, "");
        if (!cleaned) return "";
        // pasang titik setiap 3 digit
        return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // ambil angka murni dari string yang mungkin berisi titik
    function parseNumber(str) {
        str = (str || "").toString();
        const cleaned = str.replace(/[^0-9]/g, "");
        if (!cleaned) return 0;
        return Number(cleaned);
    }

    function hitungKembalian() {
        const total = parseNumber(totalInput.value);      // hidden total sudah angka polos
        const bayar = parseNumber(bayarInput.value);      // bisa "20.000" / "20000"

        const kembalian = bayar - total;

        if (kembalian >= 0) {
            kembalianInput.value   = kembalian;               // angka ke backend
            kembalianDisplay.value = formatRupiahView(kembalian); // tampilan
        } else {
            kembalianInput.value   = 0;
            kembalianDisplay.value = "";
        }
    }

    function syncHiddenKeranjang() {
        const payload = cart.map(item => ({
            id: item.id,
            qty: item.qty,
            harga_jual: item.harga
        }));
        keranjangInput.value = JSON.stringify(payload);
    }

    function renderCart() {
        cartBody.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            const subtotal = item.harga * item.qty;
            total += subtotal;

            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td>${item.nama}</td>
                <td>
                    <input type="number"
                           min="1"
                           value="${item.qty}"
                           data-index="${index}"
                           class="form-control form-control-sm qty-input">
                </td>
                <td>${formatRupiahView(item.harga)}</td>
                <td>${formatRupiahView(subtotal)}</td>
                <td>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger btn-remove"
                            data-index="${index}">
                        Hapus
                    </button>
                </td>
            `;
            cartBody.appendChild(tr);
        });

        totalDisplay.value = formatRupiahView(total);
        totalInput.value   = total; // hidden angka polos

        syncHiddenKeranjang();
        hitungKembalian();
    }

    function addToCart(id, nama, harga, qty = 1) {
        const exist = cart.find(item => String(item.id) === String(id));

        if (exist) {
            exist.qty += qty;
        } else {
            cart.push({
                id,
                nama,
                harga: Number(harga || 0),
                qty
            });
        }
        renderCart();
    }

    // ===== EVENT HANDLERS =====

    // Tambah manual
    if (btnAddItem) {
        btnAddItem.addEventListener("click", () => {
            const selected = productSelect.options[productSelect.selectedIndex];
            const id = selected.value;

            if (!id) return alert("Pilih produk dulu.");

            const harga = parseFloat(selected.dataset.harga || 0);
            const nama  = selected.dataset.nama || "Produk";
            const qty   = parseInt(qtyInput.value || "1", 10);

            addToCart(id, nama, harga, qty);
        });
    }

    // Ubah qty
    cartBody.addEventListener("input", e => {
        if (e.target.classList.contains("qty-input")) {
            const idx = e.target.getAttribute("data-index");
            let newQty = parseInt(e.target.value || "1", 10);
            if (newQty < 1 || isNaN(newQty)) newQty = 1;
            cart[idx].qty = newQty;
            renderCart();
        }
    });

    // Hapus item
    cartBody.addEventListener("click", e => {
        if (e.target.classList.contains("btn-remove")) {
            const idx = e.target.getAttribute("data-index");
            cart.splice(idx, 1);
            renderCart();
        }
    });

    // Format & hitung saat Bayar diinput
    if (bayarInput) {
        bayarInput.addEventListener("input", () => {
            bayarInput.value = formatRupiahInput(bayarInput.value);
            hitungKembalian();
        });
    }

    // Sebelum submit form → bayar harus angka polos (tanpa titik)
    if (form) {
        form.addEventListener("submit", function () {
            if (bayarInput) {
                bayarInput.value = parseNumber(bayarInput.value);
            }
        });
    }

    // Init pertama
    renderCart();
});
