// ===============================
// PRODUCTS PAGE SCRIPT
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("productContextMenu");
    const editBtn = document.getElementById("product-context-edit");
    const deleteBtn = document.getElementById("product-context-delete");

    const modalCreate = document.getElementById("modalCreateProduct");
    const modalEdit = document.getElementById("modalEditProduct");
    const openCreateBtn = document.getElementById("btnOpenCreateProduct");
    const editForm = document.getElementById("formEditProduct");

    const searchInput = document.getElementById("product-search");
    const perPageSelect = document.getElementById("per-page-select-products");

    const rows = document.querySelectorAll(".product-row");

    let selectedRow = null;

    // MODAL HELPERS
    function openModal(modal) {
        if (!modal) return;
        modal.classList.remove("d-none");
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add("d-none");
    }

    document.querySelectorAll("[data-close-modal]").forEach((btn) => {
        btn.addEventListener("click", function () {
            closeModal(modalCreate);
            closeModal(modalEdit);
        });
    });

    // CREATE MODAL
    if (openCreateBtn && modalCreate) {
        openCreateBtn.addEventListener("click", function () {
            const form = modalCreate.querySelector("form");
            if (form) form.reset();
            openModal(modalCreate);
        });
    }

    // CONTEXT MENU
    window.openProductContext = function (event, row) {
        event.preventDefault();
        selectedRow = row;

        if (!menu) return;

        menu.style.left = event.pageX + "px";
        menu.style.top = event.pageY + "px";
        menu.style.display = "block";
    };

    document.addEventListener("click", function (e) {
        if (!e.target.closest("#productContextMenu")) {
            if (menu) menu.style.display = "none";
        }
    });

    // EDIT FROM CONTEXT MENU
    if (editBtn && modalEdit && editForm) {
        editBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const id = selectedRow.dataset.id;

            const namaProduk = selectedRow.dataset.nama_produk || "";
            const sku = selectedRow.dataset.sku || "";
            const idKategori = selectedRow.dataset.id_kategori || "";
            const idSupplierDefault = selectedRow.dataset.id_supplier_default || "";
            const stok = selectedRow.dataset.stok || 0;
            const hargaBeli = selectedRow.dataset.harga_beli_terakhir || "";
            const hargaJual = selectedRow.dataset.harga_jual || "";

            editForm.action = "/products/" + id;

            const namaField = editForm.querySelector('input[name="nama_produk"]');
            const skuField = editForm.querySelector('input[name="sku"]');
            const kategoriSelect = editForm.querySelector('select[name="id_kategori"]');
            const supplierSelect = editForm.querySelector('select[name="id_supplier_default"]');
            const stokField = editForm.querySelector('input[name="stok"]');
            const hargaBeliField = editForm.querySelector('input[name="harga_beli_terakhir"]');
            const hargaJualField = editForm.querySelector('input[name="harga_jual"]');

            if (namaField) namaField.value = namaProduk;
            if (skuField) skuField.value = sku;
            if (kategoriSelect) kategoriSelect.value = idKategori;
            if (supplierSelect) supplierSelect.value = idSupplierDefault || "";
            if (stokField) stokField.value = stok;
            if (hargaBeliField) hargaBeliField.value = hargaBeli;
            if (hargaJualField) hargaJualField.value = hargaJual;

            if (menu) menu.style.display = "none";
            openModal(modalEdit);
        });
    }

    // DELETE FROM CONTEXT MENU
    if (deleteBtn) {
        deleteBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const deleteUrl = selectedRow.dataset.deleteUrl;
            if (!deleteUrl) return;

            if (menu) menu.style.display = "none";

            if (typeof openDeleteModal === "function") {
                openDeleteModal(deleteUrl);
            } else {
                if (confirm("Yakin ingin menghapus produk ini?")) {
                    const form = document.getElementById("context-delete-form");
                    if (form) {
                        form.action = deleteUrl;
                        form.submit();
                    }
                }
            }
        });
    }

    // SEARCH CLIENT-SIDE
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const term = this.value.toLowerCase();

            rows.forEach((row) => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? "" : "none";
            });
        });
    }

    // ROWS PER PAGE
    if (perPageSelect) {
        perPageSelect.addEventListener("change", function () {
            const perPage = this.value;
            const url = new URL(window.location.href);

            url.searchParams.set("per_page", perPage);
            url.searchParams.set("page", 1);

            window.location.href = url.toString();
        });
    }
});

// ANTI DOUBLE SUBMIT
const guardedForms = document.querySelectorAll("form.prevent-multi-submit");

guardedForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
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
