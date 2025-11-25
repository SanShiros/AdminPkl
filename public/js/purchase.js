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

    if (openCreateBtn && modalCreate) {
        openCreateBtn.addEventListener("click", function () {
            const form = modalCreate.querySelector("form");
            if (form) form.reset();
            openModal(modalCreate);
        });
    }

    // buka context menu dari row
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

    // EDIT
    if (editBtn && modalEdit && editForm) {
        editBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const id = selectedRow.dataset.id;
            const kodePo = selectedRow.dataset.kode_po || "";
            const idSupplier = selectedRow.dataset.id_supplier || "";
            const tanggal = selectedRow.dataset.tanggal || "";
            const total = selectedRow.dataset.total || "";
            const status = selectedRow.dataset.status || "";

            editForm.action = "/purchase_orders/" + id;

            const kodeField = editForm.querySelector('input[name="kode_po"]');
            const supplierSelect = editForm.querySelector('select[name="id_supplier"]');
            const tanggalField = editForm.querySelector('input[name="tanggal"]');
            const totalField = editForm.querySelector('input[name="total"]');
            const statusField = editForm.querySelector('input[name="status"]');

            if (kodeField) kodeField.value = kodePo;
            if (supplierSelect) supplierSelect.value = idSupplier;
            if (tanggalField) tanggalField.value = tanggal;
            if (totalField) totalField.value = total;
            if (statusField) statusField.value = status;

            if (menu) menu.style.display = "none";
            openModal(modalEdit);
        });
    }

    // DELETE
    if (deleteBtn) {
        deleteBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const deleteUrl = selectedRow.dataset.deleteUrl;
            if (!deleteUrl) return;

            if (menu) menu.style.display = "none";

            if (typeof openDeleteModal === "function") {
                openDeleteModal(deleteUrl);
            } else {
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

    // SEARCH
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

// anti double submit
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
