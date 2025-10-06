window.confirmModal = {
  modal: new bootstrap.Modal(document.getElementById('confirmModal')),
  show: function (message, title = 'Confirmation') {
    return new Promise((resolve, reject) => {
      const modalTitle = document.getElementById('confirmModalLabel');
      const modalBody = document.getElementById('confirmModalBody');
      const okBtn = document.getElementById('confirmModalOkBtn');
      const cancelBtn = document.getElementById('confirmModalCancelBtn');

      modalTitle.textContent = title;
      modalBody.textContent = message;

      const onOk = () => {
        cleanup();
        resolve(true);
      };

      const onCancel = () => {
        cleanup();
        reject(false);
      };

      const cleanup = () => {
        this.modal.hide();
        okBtn.removeEventListener('click', onOk);
        cancelBtn.removeEventListener('click', onCancel);
      };

      okBtn.addEventListener('click', onOk, { once: true });
      cancelBtn.addEventListener('click', onCancel, { once: true });

      this.modal.show();
    });
  },
};

// Alias
const showConfirmModal = (message, title) => confirmModal.show(message, title);
