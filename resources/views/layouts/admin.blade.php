<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'BambiBlog Admin' }}</title>
    <link rel="icon" href="{{ asset('favicon-bambi.ico') }}" />
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}" />
  </head>
  <body class="admin-body">
    <div class="admin-shell">
      <aside class="admin-sidebar">
        <div class="admin-brand">
          <div class="logo">
            <div class="logo-mark">B</div>
            <span>BambiBlog</span>
          </div>
          <p>Bảng điều khiển quản trị</p>
        </div>
        <div class="admin-menu">
          <a href="{{ route('admin.dashboard') }}">Tổng quan</a>
          <a href="{{ route('admin.articles.index') }}">Quản lý bài viết</a>
          <a href="{{ route('admin.categories.index') }}">Danh mục</a>
          <a href="{{ route('admin.tags.index') }}">Tag</a>
          <a href="{{ url('/admin') }}#comments">Bình luận</a>
          <a href="{{ url('/admin') }}#stats">Thống kê</a>
        </div>
        <div class="admin-footer">
          <a href="{{ url('/') }}">Về trang chính</a>
          @guest
            <a href="{{ url('/login') }}">Đăng nhập</a>
            <a href="{{ url('/register') }}">Đăng ký</a>
          @endguest
          @auth
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="admin-link admin-link--danger" type="submit">Đăng xuất</button>
            </form>
          @endauth
        </div>
      </aside>
      <main class="admin-main">
        @yield('content')
      </main>
    </div>
    <script src="{{ asset('js/custom-select.js') }}" defer></script>
    <script src="{{ asset('js/image-preview.js') }}" defer></script>
  </body>
</html>
