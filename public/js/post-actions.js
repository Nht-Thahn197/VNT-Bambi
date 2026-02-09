(function () {
  var csrfToken = '';
  var csrfMeta = document.querySelector('meta[name="csrf-token"]');
  if (csrfMeta) {
    csrfToken = csrfMeta.getAttribute('content') || '';
  }

  function jsonHeaders() {
    var headers = {
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    };
    if (csrfToken) {
      headers['X-CSRF-TOKEN'] = csrfToken;
    }
    return headers;
  }

  function updateCount(container, selector, value) {
    if (!container) {
      return;
    }
    var el = container.querySelector(selector);
    if (el) {
      el.textContent = value;
    }
  }

  function updateLikeUI(button, liked, count) {
    var container = button.closest('[data-article-id]');
    updateCount(container, '[data-like-count]', count);
    button.dataset.liked = liked ? '1' : '0';
    button.classList.toggle('is-active', liked);
    button.setAttribute('aria-pressed', liked ? 'true' : 'false');
    button.textContent = liked ? 'Đã thích' : 'Ưa thích';
  }

  function updateShareUI(button, shared, count) {
    var container = button.closest('[data-article-id]');
    updateCount(container, '[data-share-count]', count);
    if (typeof shared === 'boolean') {
      button.dataset.shared = shared ? '1' : '0';
      button.classList.toggle('is-active', shared);
      button.textContent = shared ? 'Đã chia sẻ' : 'Chia sẻ';
    }
  }

  async function handleLike(button) {
    if (button.dataset.busy === '1') {
      return;
    }
    button.dataset.busy = '1';
    button.disabled = true;

    var liked = button.dataset.liked === '1';
    var url = liked ? button.dataset.unlikeUrl : button.dataset.likeUrl;
    var method = liked ? 'DELETE' : 'POST';

    try {
      var response = await fetch(url, {
        method: method,
        headers: jsonHeaders(),
      });

      if (!response.ok) {
        throw new Error('Like request failed');
      }

      var data = await response.json();
      if (typeof data.likes_count === 'number') {
        updateLikeUI(button, data.liked, data.likes_count);
      }
    } catch (error) {
      console.error(error);
    } finally {
      button.disabled = false;
      button.dataset.busy = '0';
    }
  }

  function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
      return navigator.clipboard.writeText(text).then(function () {
        return true;
      });
    }

    return new Promise(function (resolve) {
      var textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.setAttribute('readonly', '');
      textarea.style.position = 'absolute';
      textarea.style.left = '-9999px';
      document.body.appendChild(textarea);
      textarea.select();
      var copied = false;
      try {
        copied = document.execCommand('copy');
      } catch (error) {
        copied = false;
      }
      document.body.removeChild(textarea);
      resolve(copied);
    });
  }

  async function recordShare(button) {
    var url = button.dataset.shareUrl;
    if (!url) {
      return;
    }

    var response = await fetch(url, {
      method: 'POST',
      headers: jsonHeaders(),
    });

    if (!response.ok) {
      throw new Error('Share request failed');
    }

    var data = await response.json();
    if (typeof data.shares_count === 'number') {
      updateShareUI(button, true, data.shares_count);
    }
  }

  async function handleShare(button) {
    if (button.dataset.busy === '1') {
      return;
    }
    button.dataset.busy = '1';
    button.disabled = true;

    var shareUrl = button.dataset.shareLink || window.location.href;
    var shareTitle = button.dataset.shareTitle || document.title;

    try {
      if (navigator.share) {
        await navigator.share({ title: shareTitle, url: shareUrl });
        await recordShare(button);
      } else {
        var copied = await copyToClipboard(shareUrl);
        if (copied) {
          await recordShare(button);
        }
      }
    } catch (error) {
      console.error(error);
    } finally {
      button.disabled = false;
      button.dataset.busy = '0';
    }
  }

  document.addEventListener('click', function (event) {
    var likeButton = event.target.closest('.js-like-btn');
    if (likeButton) {
      event.preventDefault();
      handleLike(likeButton);
      return;
    }

    var shareButton = event.target.closest('.js-share-btn');
    if (shareButton) {
      event.preventDefault();
      handleShare(shareButton);
    }
  });
})();
