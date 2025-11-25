// ===============================
// PURCHASE ORDERS PAGE SCRIPT
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("poContextMenu");
    const editBtn = document.getElementById("po-context-edit");
    const deleteBtn = document.getElementById("po-context-delete");

    const modalCreate = document.getElementById("modalCreatePO");
    const modalEdit = document.getElementById("modalEditPO");
    const openCreateBtn = document.getElementById("btnOpenCreatePO");
    const editForm = document.getElementById("formEditPO");

    const searchInput = document.getElementById("po-search");
    const perPageSelect = document.getElementById("per-page-select-po");

    const rows = document.querySelectorAll(".po-row");

    let selectedRow = null;

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

    document.querySelectorAll("[data-close-modal]").forEach((btn) => {
        btn.addEventListener("click", function () {
            closeModal(modalCreate);
            closeModal(modalEdit);
        });
    });

    // ===============================
    // FORMAT RUPIAH UNTUK FIELD TOTAL
    // ===============================
    function poOnlyDigits(value) {
        return (value || "").replace(/[^\d]/g, "");
    }

    function poFormatRupiah(value) {
        const digits = poOnlyDigits(value);
        if (!digits) return "";
        return new Intl.NumberFormat("id-ID").format(parseInt(digits, 10));
    }

    function poParseRupiah(value) {
        const digits = poOnlyDigits(value);
        return digits ? parseInt(digits, 10) : 0;
    }

    function setupPOCurrency(modal) {
        if (!modal) return;
        const totalInput = modal.querySelector('input[name="total"]');
        if (!totalInput) return;

        // kalau form dibuka, format value awalnya
        if (totalInput.value) {
            totalInput.value = poFormatRupiah(totalInput.value);
        }

        totalInput.addEventListener("input", function (e) {
            e.target.value = poFormatRupiah(e.target.value);
        });
    }

    // ===============================
    // CREATE MODAL
    // ===============================
    if (openCreateBtn && modalCreate) {
        openCreateBtn.addEventListener("click", function () {
            const form = modalCreate.querySelector("form");
            if (form) form.reset();

            openModal(modalCreate);
            setupPOCurrency(modalCreate);
        });
    }

    // ===============================
    // CONTEXT MENU (DIBUKA DARI BLADE)
    // ===============================
    window.openPOContext = function (event, row) {
        event.preventDefault();
        selectedRow = row;

        if (!menu) return;

        menu.style.left = event.pageX + "px";
        menu.style.top = event.pageY + "px";
        menu.style.display = "block";
    };

    document.addEventListener("click", function (e) {
        if (!e.target.closest("#poContextMenu")) {
            if (menu) menu.style.display = "none";
        }
    });

    // ===============================
    // EDIT DARI CONTEXT MENU
    // ===============================
    if (editBtn && modalEdit && editForm) {
        editBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const id = selectedRow.dataset.id;
            const kodePo = selectedRow.dataset.kode_po || "";
            const idSupplier = selectedRow.dataset.id_supplier || "";
            const tanggal = selectedRow.dataset.tanggal || "";
            const total = selectedRow.dataset.total || "";
            const status = selectedRow.dataset.status || "";

            // set action ke route resource: purchase_orders.update
            editForm.action = "/purchase_orders/" + id;

            const kodeField = editForm.querySelector('input[name="kode_po"]');
            const supplierSelect = editForm.querySelector('select[name="id_supplier"]');
            const tanggalField = editForm.querySelector('input[name="tanggal"]');
            const totalField = editForm.querySelector('input[name="total"]');
            const statusSelect = editForm.querySelector('select[name="status"]');

            if (kodeField) kodeField.value = kodePo;
            if (supplierSelect) supplierSelect.value = idSupplier;
            if (tanggalField) tanggalField.value = tanggal;
            if (totalField) totalField.value = poFormatRupiah(total);
            if (statusSelect) statusSelect.value = status;

            if (menu) menu.style.display = "none";
            openModal(modalEdit);
            setupPOCurrency(modalEdit);
        });
    }

    // ===============================
    // DELETE DARI CONTEXT MENU
    // ===============================
    if (deleteBtn) {
        deleteBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const deleteUrl = selectedRow.dataset.deleteUrl;
            if (!deleteUrl) return;

            if (menu) menu.style.display = "none";

            if (typeof openDeleteModal === "function") {
                // kalau kamu punya modal delete global
                openDeleteModal(deleteUrl);
            } else {
                // fallback confirm biasa
                if (confirm("Yakin ingin menghapus purchase order ini?")) {
                    const form = document.getElementById("context-delete-form");
                    if (form) {
                        form.action = deleteUrl;
                        form.submit();
                    }
                }
            }
        });
    }

    // ===============================
    // SEARCH CLIENT-SIDE
    // ===============================
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const term = this.value.toLowerCase();

            rows.forEach((row) => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? "" : "none";
            });
        });
    }

    // ===============================
    // ROWS PER PAGE
    // ===============================
    if (perPageSelect) {
        perPageSelect.addEventListener("change", function () {
            const perPage = this.value;
            const url = new URL(window.location.href);

            url.searchParams.set("per_page", perPage);
            url.searchParams.set("page", 1);

            window.location.href = url.toString();
        });
    }

    // aktifkan format rupiah di kedua modal (kalau page dibuka pertama kali)
    setupPOCurrency(modalCreate);
    setupPOCurrency(modalEdit);
});

// ===============================
// FORM SUBMISSION GUARD (anti double submit)
// ===============================
const guardedPOForms = document.querySelectorAll("form.prevent-multi-submit");

guardedPOForms.forEach((form) => {
    form.addEventListener("submit", function (e) {
        // ubah total dari "10.000" jadi "10000"
        const totalInput = form.querySelector('input[name="total"]');
        if (totalInput && typeof totalInput.value === "string") {
            const digits = totalInput.value.replace(/[^\d]/g, "");
            totalInput.value = digits || 0;
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

