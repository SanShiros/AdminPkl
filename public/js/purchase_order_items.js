// ===============================
// PURCHASE ORDER ITEMS PAGE SCRIPT
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    let selectedPOI = null;

    const contextMenu = document.getElementById("poiContextMenu");
    const editBtn = document.getElementById("poi-context-edit");
    const deleteBtn = document.getElementById("poi-context-delete");
    const btnOpenCreate = document.getElementById("btnOpenCreatePOI");

    const modalCreate = document.getElementById("modalCreatePOI");
    const modalEdit = document.getElementById("modalEditPOI");

    // ===============================
    // HELPER MODAL
    // ===============================
    function openModal(modal) {
        if (!modal) return;
        modal.classList.remove("d-none");
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add("d-none");
    }

    // tombol X dan Batal di kedua modal
    document.querySelectorAll("[data-close-modal]").forEach((btn) => {
        btn.addEventListener("click", function () {
            closeModal(modalCreate);
            closeModal(modalEdit);
            if (contextMenu) contextMenu.style.display = "none";
        });
    });

    // ===============================
    // HELPER FORMAT RUPIAH
    // ===============================
    function poiOnlyDigits(value) {
        return (value || "").replace(/[^\d]/g, "");
    }

    function poiFormatRupiah(value) {
        const digits = poiOnlyDigits(value);
        if (!digits) return "";
        return new Intl.NumberFormat("id-ID").format(parseInt(digits, 10));
    }

    function setupPOICurrency(modal) {
        if (!modal) return;
        const hargaInput = modal.querySelector('input[name="harga_beli"]');
        if (!hargaInput) return;

        // format nilai awal kalau ada
        if (hargaInput.value) {
            hargaInput.value = poiFormatRupiah(hargaInput.value);
        }

        hargaInput.addEventListener("input", function (e) {
            e.target.value = poiFormatRupiah(e.target.value);
        });
    }

    // ===============================
    // CONTEXT MENU OPEN (dipanggil dari <tr>)
    // ===============================
    window.openPOIContext = function (event, row) {
        event.preventDefault();
        selectedPOI = row;

        if (!contextMenu) return;

        contextMenu.style.left = event.pageX + "px";
        contextMenu.style.top = event.pageY + "px";
        contextMenu.style.display = "block";
    };

    // klik di luar => tutup context menu
    document.addEventListener("click", (e) => {
        if (!e.target.closest("#poiContextMenu")) {
            if (contextMenu) contextMenu.style.display = "none";
        }
    });

    // ===============================
    // EDIT ITEM
    // ===============================
    if (editBtn) {
        editBtn.addEventListener("click", () => {
            if (!selectedPOI) return;

            const form = document.getElementById("formEditPOI");
            if (!form) return;

            const id = selectedPOI.dataset.id;
            form.action = "/purchase_order_items/" + id;

            const productSelect = form.querySelector('select[name="id_produk"]');
            const poSelect = form.querySelector('select[name="id_po"]');
            const qtyField = form.querySelector('input[name="qty"]');
            const hargaField = form.querySelector('input[name="harga_beli"]');

            if (productSelect) productSelect.value = selectedPOI.dataset.id_produk;
            if (poSelect) poSelect.value = selectedPOI.dataset.id_po;
            if (qtyField) qtyField.value = selectedPOI.dataset.qty;

            if (hargaField) {
                // dataset.harga_beli sudah tanpa .00 dari Blade
                hargaField.value = poiFormatRupiah(selectedPOI.dataset.harga_beli || "");
            }

            if (contextMenu) contextMenu.style.display = "none";
            openModal(modalEdit);
            setupPOICurrency(modalEdit);
        });
    }

  // ===============================
// DELETE ITEM
// ===============================
if (deleteBtn) {
    deleteBtn.addEventListener("click", () => {
        if (!selectedPOI) return;

        const url = selectedPOI.dataset.deleteUrl;
        if (!url) return;

        // Coba pakai modal global dulu
        if (typeof openDeleteModal === "function") {
            // modal cantik seperti di Suppliers / Purchase Orders
            openDeleteModal(url);
        } else {
            // fallback: confirm biasa kalau fungsi belum ada
            if (!confirm("Yakin ingin menghapus item ini?")) return;

            const form = document.getElementById("context-delete-form");
            if (!form) return;

            form.action = url;
            form.submit();
        }
    });
}


    // ===============================
    // CREATE ITEM
    // ===============================
    if (btnOpenCreate && modalCreate) {
        btnOpenCreate.addEventListener("click", () => {
            const form = modalCreate.querySelector("form");
            if (form) form.reset();

            openModal(modalCreate);
            setupPOICurrency(modalCreate);
        });
    }

    // format rupiah awal kalau ada
    setupPOICurrency(modalCreate);
    setupPOICurrency(modalEdit);
});

// ===============================
// FORM SUBMISSION GUARD + UNFORMAT
// ===============================
const guardedPOIForms = document.querySelectorAll("form.prevent-multi-submit");

guardedPOIForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
        // ubah harga_beli dari "10.000" jadi "10000"
        const hargaInput = form.querySelector('input[name="harga_beli"]');
        if (hargaInput && typeof hargaInput.value === "string") {
            const digits = hargaInput.value.replace(/[^\d]/g, "");
            hargaInput.value = digits || 0;
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        if (submitBtn.dataset.submitted === "true") {
            e.preventDefault();
            return;
        }

        submitBtn.dataset.submitted = "true";
        submitBtn.disabled = true;

        const originalText = submitBtn.innerText;
        submitBtn.dataset.originalText = originalText;
        submitBtn.innerText = "Memproses...";
    });
});
