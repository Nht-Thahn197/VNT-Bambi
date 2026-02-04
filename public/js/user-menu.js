(function () {
  function closeAll() {
    document.querySelectorAll('.user-menu.is-open').forEach(function (menu) {
      menu.classList.remove('is-open');
      var button = menu.querySelector('.user-menu__button');
      if (button) {
        button.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function init() {
    document.querySelectorAll('[data-user-menu]').forEach(function (menu) {
      var button = menu.querySelector('.user-menu__button');
      if (!button) {
        return;
      }

      button.addEventListener('click', function (event) {
        event.stopPropagation();
        var isOpen = menu.classList.contains('is-open');
        closeAll();
        if (!isOpen) {
          menu.classList.add('is-open');
          button.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  document.addEventListener('click', function () {
    closeAll();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeAll();
    }
  });

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
