@php
    $displayName = $user?->user_name ?? 'Khách';
    $displayRole = $user?->role ?? 'Thành viên';
    $displayStatus = $user && $user->status ? 'Đang hoạt động' : 'Đã khóa';
    $avatar = $user?->avatar_url ?? asset('images/avatar/default-avatar.png');
    $metrics = array_merge(
        ['articles' => 0, 'articles_week' => 0, 'comments' => 0, 'categories' => 0],
        is_array($metrics ?? null) ? $metrics : []
    );
@endphp

<div class="card profile-card">
  <div style="display: flex; align-items: center; gap: 12px;">
    <img class="avatar" src="{{ $avatar }}" alt="avatar" />
    <div>
      <div class="name">{{ $displayName }}</div>
      <div class="meta">{{ $displayRole }}</div>
    </div>
  </div>
  <div class="meta">{{ $displayStatus }}</div>
  <a class="btn btn-primary" href="{{ route('articles.create') }}">Tạo bài viết</a>
</div>

<div class="card">
  <h3>Menu nhanh</h3>
  <ul class="menu-list">
    <li><a href="{{ route('home') }}">Bảng tin <span>{{ $metrics['articles'] ?? 0 }}</span></a></li>
    <li><a href="{{ route('articles.index') }}">Bài viết mới <span>{{ $metrics['articles_week'] ?? 0 }}</span></a></li>
    <li><a href="{{ route('articles.index') }}">Danh mục <span>{{ $metrics['categories'] ?? 0 }}</span></a></li>
  </ul>
</div>

<div class="card">
  <h3>Chuyên mục</h3>
  <div class="tag-cloud">
    @foreach ($categories ?? [] as $category)
      <a class="pill" href="{{ route('home', ['category' => $category->id]) }}">{{ $category->name }}</a>
    @endforeach
  </div>
</div>

<div class="card">
  <h3>Tag nổi bật</h3>
  <div class="tag-cloud">
    @foreach ($tags ?? [] as $tag)
      <a class="tag" href="{{ route('home', ['tag' => $tag->id]) }}">#{{ $tag->name }}</a>
    @endforeach
  </div>
</div>
