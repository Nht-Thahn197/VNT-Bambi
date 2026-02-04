<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'BambiBlog' }}</title>
    <link rel="stylesheet" href="{{ asset('css/blog.css') }}" />
  </head>
  <body>
    <header class="topbar">
      <div class="container topbar-inner">
        <div class="logo">
          <div class="logo-mark">B</div>
          <span>BambiBlog</span>
        </div>
        <div class="search-bar">
          <input type="text" placeholder="Tìm kiếm bài viết, tag, tác giả..." />
          <span>⌕</span>
        </div>
        <nav class="nav-links">
          <a href="{{ url('/') }}">Trang chủ</a>
          <a href="{{ url('/articles') }}">Bài viết</a>
          @auth
            @if (auth()->user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}">Quản trị</a>
            @endif
          @endauth
        </nav>
        <div class="actions-right">
          @guest
            <a class="btn btn-outline" href="{{ url('/login') }}">Đăng nhập</a>
            <a class="btn btn-primary" href="{{ url('/register') }}">Đăng ký</a>
          @endguest
          @auth
            <div class="user-menu" data-user-menu>
              <button class="user-menu__button" type="button" aria-haspopup="true" aria-expanded="false">
                <span class="user-menu__icon" aria-hidden="true">
                  <span></span>
                  <span></span>
                  <span></span>
                </span>
              </button>
              <div class="user-menu__panel" role="menu">
                <a href="{{ route('profile.show') }}" role="menuitem">Trang cá nhân</a>
                <form method="POST" action="{{ route('logout') }}" role="none">
                  @csrf
                  <button class="user-menu__danger" type="submit" role="menuitem">Đăng xuất</button>
                </form>
              </div>
            </div>
          @endauth
        </div>
      </div>
    </header>

    <main class="container">
      <div class="main-grid">
        <aside class="sidebar left">
          @include('partials.sidebar-left', [
              'user' => $user ?? null,
              'categories' => $categories ?? [],
              'tags' => $tags ?? [],
              'metrics' => $metrics ?? [],
          ])
        </aside>

        <section>
          @yield('content')
        </section>

        <aside class="sidebar right">
          @include('partials.sidebar-right', [
              'trending' => $trending ?? [],
              'people' => $people ?? [],
          ])
        </aside>
      </div>
    </main>

    <footer class="footer">
      <div class="container">BambiBlog · Kết nối cộng đồng, lan tỏa tri thức.</div>
    </footer>
    <script src="{{ asset('js/custom-select.js') }}" defer></script>
    <script src="{{ asset('js/user-menu.js') }}" defer></script>
    <script src="{{ asset('js/image-preview.js') }}" defer></script>
  </body>
</html>
