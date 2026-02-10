(function () {
  var modal = document.querySelector('[data-confirm-modal]');
  if (!modal) {
    return;
  }

  var titleEl = modal.querySelector('[data-confirm-title]');
  var messageEl = modal.querySelector('[data-confirm-message]');
  var okButton = modal.querySelector('[data-confirm-ok]');
  var cancelButtons = modal.querySelectorAll('[data-confirm-cancel]');
  var activeForm = null;
  var lastFocused = null;

  function setText(el, value, fallback) {
    if (!el) {
      return;
    }
    el.textContent = value || fallback || '';
  }

  function openModal(options) {
    activeForm = options.form || null;
    lastFocused = document.activeElement;
    setText(titleEl, options.title, 'Xac nhan');
    setText(messageEl, options.message, 'Ban chac chan muon thuc hien thao tac nay?');

    if (okButton) {
      okButton.textContent = options.okLabel || 'Dong y';
      okButton.setAttribute('class', options.okClass || 'btn btn-primary');
    }

    var cancelButton = modal.querySelector('[data-confirm-cancel]');
    if (cancelButton) {
      cancelButton.textContent = options.cancelLabel || 'Huy';
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');

    if (okButton) {
      okButton.focus();
    }
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');

    if (lastFocused && typeof lastFocused.focus === 'function') {
      lastFocused.focus();
    }

    activeForm = null;
  }

  document.addEventListener(
    'submit',
    function (event) {
      var form = event.target;
      if (!form || !form.matches('[data-confirm]')) {
        return;
      }
      if (form.dataset.confirmBypass === '1') {
        form.dataset.confirmBypass = '0';
        return;
      }

      event.preventDefault();
      openModal({
        form: form,
        message: form.dataset.confirm,
        title: form.dataset.confirmTitle,
        okLabel: form.dataset.confirmOk,
        okClass: form.dataset.confirmOkClass,
        cancelLabel: form.dataset.confirmCancel,
      });
    },
    true
  );

  if (okButton) {
    okButton.addEventListener('click', function () {
      if (!activeForm) {
        closeModal();
        return;
      }
      var form = activeForm;
      closeModal();
      form.dataset.confirmBypass = '1';
      form.submit();
    });
  }

  cancelButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      closeModal();
    });
  });

  modal.addEventListener('click', function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  document.addEventListener('keydown', function (event) {
    if (!modal.classList.contains('is-open')) {
      return;
    }
    if (event.key === 'Escape') {
      event.preventDefault();
      closeModal();
    }
  });
})();
