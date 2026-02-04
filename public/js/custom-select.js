(function () {
  function closeAll(except) {
    document.querySelectorAll('.custom-select.is-open').forEach(function (el) {
      if (el !== except) {
        el.classList.remove('is-open');
      }
    });
  }

  function buildSelect(select) {
    if (select.dataset.customSelect === '1') {
      return;
    }

    if (select.multiple || select.size > 1) {
      return;
    }

    select.dataset.customSelect = '1';
    select.classList.add('custom-select__native');

    var wrapper = document.createElement('div');
    wrapper.className = 'custom-select';

    var trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'custom-select__trigger';

    var label = document.createElement('span');
    label.textContent = select.options[select.selectedIndex]
      ? select.options[select.selectedIndex].text
      : '';

    var caret = document.createElement('span');
    caret.className = 'custom-select__caret';

    trigger.appendChild(label);
    trigger.appendChild(caret);

    var optionsWrap = document.createElement('div');
    optionsWrap.className = 'custom-select__options';

    Array.prototype.slice.call(select.options).forEach(function (option) {
      var optionBtn = document.createElement('button');
      optionBtn.type = 'button';
      optionBtn.className = 'custom-select__option';
      optionBtn.textContent = option.text;
      optionBtn.dataset.value = option.value;

      if (option.disabled) {
        optionBtn.classList.add('is-disabled');
        optionBtn.disabled = true;
      }

      if (option.selected) {
        optionBtn.classList.add('is-selected');
      }

      optionBtn.addEventListener('click', function () {
        if (optionBtn.disabled) {
          return;
        }

        select.value = optionBtn.dataset.value;
        select.dispatchEvent(new Event('change', { bubbles: true }));
        label.textContent = optionBtn.textContent;

        optionsWrap.querySelectorAll('.custom-select__option').forEach(function (btn) {
          btn.classList.remove('is-selected');
        });
        optionBtn.classList.add('is-selected');

        wrapper.classList.remove('is-open');
      });

      optionsWrap.appendChild(optionBtn);
    });

    select.parentNode.insertBefore(wrapper, select);
    wrapper.appendChild(select);
    wrapper.appendChild(trigger);
    wrapper.appendChild(optionsWrap);

    trigger.addEventListener('click', function () {
      var isOpen = wrapper.classList.contains('is-open');
      if (isOpen) {
        wrapper.classList.remove('is-open');
        return;
      }

      closeAll(wrapper);
      wrapper.classList.add('is-open');
    });
  }

  function init() {
    document.querySelectorAll('select').forEach(buildSelect);
  }

  document.addEventListener('click', function (event) {
    if (!event.target.closest('.custom-select')) {
      closeAll();
    }
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
