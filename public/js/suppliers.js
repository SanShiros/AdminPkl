// ===============================
// SUPPLIERS PAGE SCRIPT
// ===============================
document.addEventListener('DOMContentLoaded', function () {
    // ELEMENT UTAMA
    const menu          = document.getElementById('supplierContextMenu');
    const editBtn       = document.getElementById('supplier-context-edit');
    const deleteBtn     = document.getElementById('supplier-context-delete');

    const modalCreate   = document.getElementById('modalCreateSupplier');
    const modalEdit     = document.getElementById('modalEditSupplier');
    const openCreateBtn = document.getElementById('btnOpenCreateSupplier');
    const editForm      = document.getElementById('formEditSupplier');

    const searchInput   = document.getElementById('supplier-search');
    const perPageSelect = document.getElementById('per-page-select');

    const rows          = document.querySelectorAll('.supplier-row');

    let selectedRow = null;

    // ===============================
    // HELPER MODAL
    // ===============================
    function openModal(modal) {
        if (!modal) return;
        modal.classList.remove('d-none');
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add('d-none');
    }

    // tombol close di semua modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function () {
            closeModal(modalCreate);
            closeModal(modalEdit);
        });
    });

    // ===============================
    // CREATE MODAL
    // ===============================
    if (openCreateBtn && modalCreate) {
        openCreateBtn.addEventListener('click', function () {
            const form = modalCreate.querySelector('form');
            if (form) form.reset();
            openModal(modalCreate);
        });
    }

    // ===============================
    // CONTEXT MENU (DIBUKA DARI BLADE)
    // ===============================
    window.openSupplierContext = function (event, row) {
        event.preventDefault();
        selectedRow = row;

        if (!menu) return;

        menu.style.left = event.pageX + 'px';
        menu.style.top  = event.pageY + 'px';
        menu.style.display = 'block';
    };

    // klik di luar menu -> tutup
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#supplierContextMenu')) {
            if (menu) menu.style.display = 'none';
        }
    });

    // ===============================
    // EDIT DARI CONTEXT MENU
    // ===============================
    if (editBtn && modalEdit && editForm) {
        editBtn.addEventListener('click', function () {
            if (!selectedRow) return;

            const id      = selectedRow.dataset.id;
            const nama    = selectedRow.dataset.nama    || '';
            const alamat  = selectedRow.dataset.alamat  || '';
            const telepon = selectedRow.dataset.telepon || '';
            const email   = selectedRow.dataset.email   || '';

            // SET ACTION FORM (sesuai route resource: suppliers.update)
            // Kalau route-mu ada prefix (misal /admin/suppliers), ganti di sini.
            editForm.action = '/suppliers/' + id;

            const namaField    = editForm.querySelector('input[name="nama_supplier"]');
            const alamatField  = editForm.querySelector('textarea[name="alamat"]');
            const teleponField = editForm.querySelector('input[name="telepon"]');
            const emailField   = editForm.querySelector('input[name="email"]');

            if (namaField)    namaField.value    = nama;
            if (alamatField)  alamatField.value  = alamat;
            if (teleponField) teleponField.value = telepon;
            if (emailField)   emailField.value   = email;

            if (menu)   menu.style.display = 'none';
            openModal(modalEdit);
        });
    }

    // ===============================
    // DELETE DARI CONTEXT MENU
    // ===============================
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            if (!selectedRow) return;

            const deleteUrl = selectedRow.dataset.deleteUrl;
            if (!deleteUrl) return;

            if (menu) menu.style.display = 'none';

            // pakai modal delete global (sama seperti di Category)
            if (typeof openDeleteModal === 'function') {
                openDeleteModal(deleteUrl);
            } else {
                // fallback: confirm biasa
                if (confirm('Yakin ingin menghapus supplier ini?')) {
                    const form = document.getElementById('context-delete-form');
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
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // ===============================
    // ROWS PER PAGE
    // ===============================
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function () {
            const perPage = this.value;
            const url = new URL(window.location.href);

            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);

            window.location.href = url.toString();
        });
    }
});

// ===============================
// FORM SUBMISSION GUARD (anti double submit)
// ===============================
const guardedForms = document.querySelectorAll('form.prevent-multi-submit');

guardedForms.forEach((form) => {
    form.addEventListener('submit', function (e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        if (submitBtn.dataset.submitted === 'true') {
            e.preventDefault();
            return;
        }

        submitBtn.dataset.submitted = 'true';
        submitBtn.disabled = true;

        const originalText = submitBtn.innerText;
        submitBtn.dataset.originalText = originalText;
        submitBtn.innerText = 'Memproses...';
    });
});
