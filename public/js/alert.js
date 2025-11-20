  // GLOBAL DELETE MODAL
  const deleteModal = document.getElementById('deleteConfirmModal');
  const deleteForm  = document.getElementById('deleteConfirmForm');
  const btnCancel   = document.getElementById('deleteConfirmCancel');
  const btnClose    = document.getElementById('deleteConfirmClose');

  function openDeleteModal(url) {
      if (!deleteModal || !deleteForm) return;
      deleteForm.action = url;
      deleteModal.classList.remove('hidden');
      deleteModal.classList.add('flex');
  }

  function closeDeleteModal() {
      if (!deleteModal) return;
      deleteModal.classList.add('hidden');
      deleteModal.classList.remove('flex');
  }

  if (btnCancel) btnCancel.addEventListener('click', closeDeleteModal);
  if (btnClose)  btnClose.addEventListener('click', closeDeleteModal);

  if (deleteModal) {
      deleteModal.addEventListener('click', function (e) {
          if (e.target === deleteModal) closeDeleteModal();
      });
  }

  // supaya bisa dipanggil dari inline onclick di Blade
  window.openDeleteModal = openDeleteModal;