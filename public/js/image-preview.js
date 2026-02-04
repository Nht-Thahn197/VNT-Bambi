(function () {
  function updatePreview(input, preview, link) {
    var file = input.files && input.files[0];
    if (!file || !file.type || !file.type.startsWith('image/')) {
      return;
    }

    var oldUrl = preview.dataset.objectUrl;
    if (oldUrl) {
      URL.revokeObjectURL(oldUrl);
    }

    var url = URL.createObjectURL(file);
    preview.src = url;
    preview.dataset.objectUrl = url;
    if (link) {
      link.href = url;
    }
  }

  function init() {
    document.querySelectorAll('[data-image-preview-input]').forEach(function (input) {
      var targetId = input.dataset.imagePreviewTarget;
      if (!targetId) {
        return;
      }

      var preview = document.getElementById(targetId);
      if (!preview) {
        return;
      }

      var link = preview.closest('[data-image-preview-link]');

      input.addEventListener('change', function () {
        updatePreview(input, preview, link);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
