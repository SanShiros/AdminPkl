document.addEventListener('DOMContentLoaded', function () {
    // ELEMENT REFERENSI
    const table = document.getElementById('category-table');
    const rows = table ? table.querySelectorAll('.category-row') : [];

    const contextMenu   = document.getElementById('categoryContextMenu');
    const ctxEditBtn    = document.getElementById('category-context-edit');
    const ctxDeleteBtn  = document.getElementById('category-context-delete');

    const btnOpenCreate = document.getElementById('btnOpenCreateCategory');
    const modalCreate   = document.getElementById('modalCreateCategory');
    const modalEdit     = document.getElementById('modalEditCategory');
    const editForm      = document.getElementById('formEditCategory');

    const searchInput   = document.getElementById('category-search');
    const perPageSelect = document.getElementById('category-per-page-select');

    let selectedRow = null;

    /* ================================
       HELPER: MODAL (CREATE / EDIT)
    ================================= */
    function openModal(modal) {
        if (!modal) return;
        modal.classList.remove('d-none');
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add('d-none');
    }

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function () {
            closeModal(modalCreate);
            closeModal(modalEdit);
        });
    });

    /* ================================
       CREATE MODAL
    ================================= */
    if (btnOpenCreate && modalCreate) {
        btnOpenCreate.addEventListener('click', function () {
            const form = modalCreate.querySelector('form');
            if (form) form.reset();
            openModal(modalCreate);
        });
    }

    /* ================================
       CONTEXT MENU (ROW KLIK / KANAN)
    ================================= */
    function openContextMenu(e, row) {
        e.preventDefault();
        selectedRow = row;

        if (!contextMenu) return;

        contextMenu.style.left = e.pageX + 'px';
        contextMenu.style.top  = e.pageY + 'px';
        contextMenu.style.display = 'block';
    }

    function closeContextMenu() {
        if (!contextMenu) return;
        contextMenu.style.display = 'none';
    }

    rows.forEach(row => {
        row.addEventListener('contextmenu', function (e) {
            openContextMenu(e, row);
        });

        row.addEventListener('click', function (e) {
            openContextMenu(e, row);
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#categoryContextMenu')) {
            closeContextMenu();
        }
    });

    /* ================================
       EDIT DARI CONTEXT MENU
    ================================= */
    if (ctxEditBtn && modalEdit && editForm) {
        ctxEditBtn.addEventListener('click', function () {
            if (!selectedRow) return;

            const id          = selectedRow.dataset.id;
            const nama        = selectedRow.dataset.nama || '';
            const keterangan  = selectedRow.dataset.keterangan || '';

            editForm.action = '/categories/' + id;

            const namaInput   = editForm.querySelector('input[name="nama"]');
            const ketTextarea = editForm.querySelector('textarea[name="keterangan"]');

            if (namaInput)   namaInput.value   = nama;
            if (ketTextarea) ketTextarea.value = keterangan;

            closeContextMenu();
            openModal(modalEdit);
        });
    }

    /* ================================
       DELETE DARI CONTEXT MENU
    ================================= */
    if (ctxDeleteBtn) {
        ctxDeleteBtn.addEventListener('click', function () {
            if (!selectedRow) return;

            const deleteUrl = selectedRow.dataset.deleteUrl;
            if (!deleteUrl) return;

            closeContextMenu();
            // 🔴 Panggil modal delete global (di layout)
            openDeleteModal(deleteUrl);
        });
    }

    /* ================================
       SEARCH (CLIENT-SIDE)
    ================================= */
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();

            rows.forEach(row => {
                const nama = (row.dataset.nama || '').toLowerCase();
                const ket  = (row.dataset.keterangan || '').toLowerCase();

                row.style.display =
                    nama.includes(q) || ket.includes(q) ? '' : 'none';
            });
        });
    }

    /* ================================
       ROWS PER PAGE (per_page)
    ================================= */
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
