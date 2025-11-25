document.addEventListener("DOMContentLoaded", function () {
    let cart = []; // [{id, nama, harga, qty}]

    const cartBody = document.querySelector("#cart-table tbody");
    const productSelect = document.querySelector("#product-select");
    const qtyInput = document.querySelector("#qty-input");
    const btnAddItem = document.querySelector("#btn-add-item");
    const totalDisplay = document.querySelector("#total-display");
    const totalInput = document.querySelector("#total-input");
    const keranjangInput = document.querySelector("#keranjang-input");
    const bayarInput = document.querySelector("#bayar-input");
    const kembalianInput = document.querySelector("#kembalian-input"); // hidden (numeric)
    const kembalianDisplay = document.querySelector("#kembalian-display"); // tampilan Rp

    function formatRupiah(num) {
        num = Number(num || 0);
        return "Rp " + num.toLocaleString("id-ID");
    }

  function hitungKembalian() {
    const total = parseFloat(totalInput.value || 0);
    const bayar = parseFloat(bayarInput.value || 0);
    const kembalian = bayar - total;

    if (kembalian >= 0) {
        // angka murni untuk backend
        kembalianInput.value = kembalian;

        // tampilan Rp untuk user
        kembalianDisplay.value = formatRupiah(kembalian);
    } else {
        kembalianInput.value = 0;
        kembalianDisplay.value = '';
    }
}


    function syncHiddenKeranjang() {
        const payload = cart.map((item) => ({
            id: item.id, // id_produk
            qty: item.qty,
            harga_jual: item.harga, // numeric
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

    btnAddItem.addEventListener("click", function () {
        const selected = productSelect.options[productSelect.selectedIndex];
        const id = selected.value;

        if (!id) {
            alert("Pilih produk dulu.");
            return;
        }

        const harga = parseFloat(selected.dataset.harga || 0);
        const nama = selected.dataset.nama || "Produk";
        const qty = parseInt(qtyInput.value || "1", 10);

        if (qty < 1) {
            alert("Qty minimal 1");
            return;
        }

        const existing = cart.find((item) => String(item.id) === String(id));
        if (existing) {
            existing.qty += qty;
        } else {
            cart.push({
                id: id, // id_produk
                nama: nama,
                harga: harga,
                qty: qty,
            });
        }

        renderCart();
    });

    // Ubah qty di tabel
    cartBody.addEventListener("input", function (e) {
        if (e.target.classList.contains("qty-input")) {
            const idx = e.target.getAttribute("data-index");
            let newQty = parseInt(e.target.value || "1", 10);
            if (newQty < 1 || isNaN(newQty)) newQty = 1;
            cart[idx].qty = newQty;
            renderCart();
        }
    });

    // Hapus item
    cartBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-remove")) {
            const idx = e.target.getAttribute("data-index");
            cart.splice(idx, 1);
            renderCart();
        }
    });

    // Hitung kembalian saat bayar berubah
    bayarInput.addEventListener("input", hitungKembalian);

    // Inisialisasi awal
    renderCart();
});
