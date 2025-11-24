// ===============================
// SALES PAGE SCRIPT
// ===============================
document.addEventListener("DOMContentLoaded", function () {
    const menu = document.getElementById("saleContextMenu");
    const editBtn = document.getElementById("sale-context-edit");
    const deleteBtn = document.getElementById("sale-context-delete");

    const modalCreate = document.getElementById("modalCreateSale");
    const modalEdit = document.getElementById("modalEditSale");
    const openCreateBtn = document.getElementById("btnOpenCreateSale");
    const editForm = document.getElementById("formEditSale");

    const searchInput = document.getElementById("sale-search");
    const perPageSelect = document.getElementById("per-page-select-sales");

    const rows = document.querySelectorAll(".sale-row");

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
    window.openSaleContext = function (event, row) {
        event.preventDefault();
        selectedRow = row;

        if (!menu) return;

        menu.style.left = event.pageX + "px";
        menu.style.top = event.pageY + "px";
        menu.style.display = "block";
    };

    document.addEventListener("click", function (e) {
        if (!e.target.closest("#saleContextMenu")) {
            if (menu) menu.style.display = "none";
        }
    });

    // EDIT FROM CONTEXT MENU
    if (editBtn && modalEdit && editForm) {
        editBtn.addEventListener("click", function () {
            if (!selectedRow) return;

            const id = selectedRow.dataset.id;

            const kodeNota = selectedRow.dataset.kode_nota || "";
            const tanggal = selectedRow.dataset.tanggal || "";
            const total = selectedRow.dataset.total || "";
            const bayar = selectedRow.dataset.bayar || "";
            const kembalian = selectedRow.dataset.kembalian || "";
            const metode = selectedRow.dataset.metode_bayar || "";

            editForm.action = "/sales/" + id;

            editForm.querySelector('input[name="kode_nota"]').value = kodeNota;
            editForm.querySelector('input[name="tanggal"]').value = tanggal;
            editForm.querySelector('input[name="total"]').value = total;
            editForm.querySelector('input[name="bayar"]').value = bayar;
            editForm.querySelector('input[name="kembalian"]').value = kembalian;
            editForm.querySelector('select[name="metode_bayar"]').value = metode;

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
                if (confirm("Yakin ingin menghapus data sales ini?")) {
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
