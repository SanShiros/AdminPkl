document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.supplier-row');
    const menu = document.getElementById('rowContextMenu');
    const deleteForm = document.getElementById('context-delete-form');
    const editBtn = document.getElementById('context-edit');
    const deleteBtn = document.getElementById('context-delete');

    // ==== ELEMEN MODAL ====
    const modalCreate = document.getElementById('modalCreateSupplier');
    const modalEdit = document.getElementById('modalEditSupplier');
    const openCreateBtn = document.getElementById('btnOpenCreateSupplier');
    const editForm = document.getElementById('formEditSupplier');
    const editRouteTemplate = "{{ route('suppliers.update', ['supplier' => '__ID__']) }}"; // ⬅ Perlu diganti di server-side

    let currentRow = null;
    let currentDeleteUrl = null;

    function openModal(modal) {
        if (modal) modal.classList.remove('d-none');
    }

    function closeModal(modal) {
        if (modal) modal.classList.add('d-none');
    }

    // tombol "Add supplier" -> buka modal create
    if (openCreateBtn && modalCreate) {
        openCreateBtn.addEventListener('click', function() {
            openModal(modalCreate);
        });
    }

    // tombol close di semua modal
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            closeModal(modalCreate);
            closeModal(modalEdit);
        });
    });

    // klik kanan tiap baris
    rows.forEach(row => {
        row.addEventListener('contextmenu', function(e) {
            e.preventDefault();

            currentRow = this;
            currentDeleteUrl = this.dataset.deleteUrl;

            menu.style.top = e.pageY + 'px';
            menu.style.left = e.pageX + 'px';
            menu.classList.add('show');
        });
    });

    // klik di luar menu -> hide
    document.addEventListener('click', function(e) {
        if (!menu.contains(e.target)) {
            menu.classList.remove('show');
        }
    });

    // EDIT dari context menu -> buka modal edit
    if (editBtn) {
        editBtn.addEventListener('click', function() {
            if (!currentRow || !modalEdit || !editForm) return;

            const id = currentRow.dataset.id;
            const nama = currentRow.dataset.nama || '';
            const alamat = currentRow.dataset.alamat || '';
            const telepon = currentRow.dataset.telepon || '';
            const email = currentRow.dataset.email || '';

            // Set action form ke route update
            editForm.action = editRouteTemplate.replace('__ID__', id);

            // Isi field
            const namaField = editForm.querySelector('input[name="nama_supplier"]');
            const alamatField = editForm.querySelector('textarea[name="alamat"]');
            const teleponField = editForm.querySelector('input[name="telepon"]');
            const emailField = editForm.querySelector('input[name="email"]');

            if (namaField) namaField.value = nama;
            if (alamatField) alamatField.value = alamat;
            if (teleponField) teleponField.value = telepon;
            if (emailField) emailField.value = email;

            menu.classList.remove('show');
            openModal(modalEdit);
        });
    }

    // DELETE
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (currentDeleteUrl) {
                if (confirm('Yakin ingin menghapus supplier ini?')) {
                    deleteForm.action = currentDeleteUrl;
                    deleteForm.submit();
                }
            }
        });
    }

    // SEARCH
    const searchInput = document.getElementById('supplier-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // ROWS PER PAGE
    const perPageSelect = document.getElementById('per-page-select');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            const perPage = this.value;
            const url = new URL(window.location.href);

            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1);

            window.location.href = url.toString();
        });
    }
});

// Perhatian: Script di bawah ini mungkin tidak digunakan karena tidak ada elemen #delModal di file Blade Anda.
// document.addEventListener('DOMContentLoaded', function() {
//     const modalEl = document.getElementById('delModal'); // <div id="delModal">
//     const delForm = document.getElementById('delForm'); // <form id="delForm">
//
//     // instance modal bootstrap (JS murni)
//     const deletionModal = new bootstrap.Modal(modalEl);
//
//     // fungsi global, supaya bisa dipanggil dari blade: onclick="handleDeletion(…)"
//     window.handleDeletion = function(id) {
//         // set action form
//         delForm.action = '/gtk/' + id; // atau route('gtk.destroy', id) kalau mau dibikin dinamis di Blade
//         deletionModal.show(); // buka modal
//     };
// });

// FORM SUBMISSION GUARD
const guardedForms = document.querySelectorAll('form.prevent-multi-submit');

guardedForms.forEach((form) => {
    form.addEventListener('submit', function(e) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        // kalau sudah pernah di-submit, cegah submit lagi
        if (submitBtn.dataset.submitted === 'true') {
            e.preventDefault();
            return;
        }

        // tandai dan disable
        submitBtn.dataset.submitted = 'true';
        submitBtn.disabled = true;

        // optional: kasih teks loading
        const originalText = submitBtn.innerText;
        submitBtn.dataset.originalText = originalText;
        submitBtn.innerText = 'Memproses...';
    });
});