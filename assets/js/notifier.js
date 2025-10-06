window.notifier = {
  toastContainer: $('.toast-container'),
  show: function (message, type = 'info', delay = 5000) {
    const iconMap = {
      success: 'fa-check-circle',
      error: 'fa-times-circle',
      warning: 'fa-exclamation-triangle',
      info: 'fa-info-circle',
    };
    const bgMap = {
      success: 'bg-success',
      error: 'bg-danger',
      warning: 'bg-warning',
      info: 'bg-info',
    };

    const toastId = 'toast-' + Date.now();
    const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgMap[type]}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="${delay}">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fa ${iconMap[type]} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

    this.toastContainer.append(toastHtml);
    const toastElement = new bootstrap.Toast(document.getElementById(toastId));

    // Remove o elemento do DOM depois de o toast ser escondido
    document
      .getElementById(toastId)
      .addEventListener('hidden.bs.toast', function () {
        this.remove();
      });

    toastElement.show();
  },
};
