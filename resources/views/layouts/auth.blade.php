<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'BambiBlog Auth' }}</title>
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}" />
  </head>
  <body class="auth-body">
    <div class="auth-wrap">
      <div class="auth-panel">
        <div class="auth-info">
          <div class="logo">
            <div class="logo-mark">B</div>
            <span>BambiBlog</span>
          </div>
          <h2>Chia sẻ câu chuyện của bạn</h2>
          <p>
            Xây dựng hồ sơ, theo dõi tác giả yêu thích và khám phá cộng đồng sáng
            tạo đang phát triển.
          </p>
          <div class="pill orange">Bảo mật · Nhanh · Thân thiện</div>
        </div>
        <div class="auth-form">
          @yield('content')
        </div>
      </div>
    </div>
    <script src="{{ asset('js/image-preview.js') }}" defer></script>
  </body>
</html>
