@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Bảng tin hôm nay</h1>
      <div class="post-meta">Cập nhật mới nhất · {{ $posts->count() }} bài viết</div>
    </div>
    <div class="actions-right">
      <a class="btn btn-outline" href="#feed-filters">Tùy chỉnh bảng tin</a>
      <a class="btn btn-primary" href="{{ route('articles.create') }}">Tạo bài viết</a>
    </div>
  </div>

  @if (session('success'))
    <div class="post-meta" style="color: #0f5132; font-weight: 600; margin-bottom: 12px;">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <form class="card filter-bar" id="feed-filters" method="GET" action="{{ route('home') }}">
    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Tìm kiếm bài viết..." />
    <select name="category">
      <option value="">Chọn chuyên mục</option>
      @foreach ($categories as $category)
        <option value="{{ $category->id }}" @selected(($filters['category'] ?? null) == $category->id)>
          {{ $category->name }}
        </option>
      @endforeach
    </select>
    <select name="tag">
      <option value="">Chọn tag</option>
      @foreach ($tags as $tag)
        <option value="{{ $tag->id }}" @selected(($filters['tag'] ?? null) == $tag->id)>
          #{{ $tag->name }}
        </option>
      @endforeach
    </select>
    <select name="feed">
      <option value="" @selected(($filters['feed'] ?? '') === '')>Tất cả</option>
      <option value="following" @selected(($filters['feed'] ?? '') === 'following')>Đang theo dõi</option>
    </select>
    <button class="btn btn-outline" type="submit">Lọc</button>
  </form>

  <div class="card composer">
    <form method="POST" action="{{ route('articles.store') }}" style="display: flex; gap: 16px; width: 100%;">
      @csrf
      <input type="hidden" name="redirect" value="home" />
      <img class="avatar" src="{{ $user?->avatar_url ?? 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80' }}" alt="avatar" />
      <div style="flex: 1; display: grid; gap: 12px;">
        <input class="input" type="text" name="title" value="{{ old('title') }}" placeholder="Tiêu đề bài viết" required />
        <textarea class="input" name="content" rows="3" placeholder="Chia sẻ một ý tưởng mới..." required>{{ old('content') }}</textarea>
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
          <select class="input" name="category_id" required>
            <option value="">Chọn chuyên mục</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          <button class="btn btn-primary" type="submit">Đăng</button>
        </div>
      </div>
    </form>
  </div>

  <div class="feed">
    @forelse ($posts as $post)
      <article class="card post-card">
        <div class="post-header">
          <div style="display: flex; gap: 12px; align-items: center;">
            <img class="avatar" src="{{ $post->user?->avatar_url ?? 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80' }}" alt="avatar" />
            <div>
              <div style="font-weight: 600;">{{ $post->user?->user_name ?? 'Ẩn danh' }}</div>
              <div class="post-meta">
                {{ $post->create_at?->format('d M Y') ?? '---' }} · {{ $post->category?->name ?? 'Chưa phân loại' }}
              </div>
            </div>
          </div>
          <span class="pill">{{ $post->reading_time }}</span>
        </div>

        <h2 class="post-title">
          <a href="{{ route('articles.show', $post) }}">{{ $post->title }}</a>
        </h2>
        <div class="post-excerpt">{{ $post->excerpt }}</div>

        @if ($post->thumbnail)
          <img class="post-media" src="{{ $post->thumbnail }}" alt="thumbnail" />
        @endif

        <div class="post-meta">
          @foreach ($post->tags as $tag)
            <span class="tag">#{{ $tag->name }}</span>
          @endforeach
        </div>

        <div class="post-actions">
          <div class="actions-left">
            <span class="stat">Bình luận: {{ $post->comments_count }}</span>
          </div>
          <div class="actions-right">
            <button class="btn btn-ghost">Ưa thích</button>
            <a class="btn btn-outline" href="{{ route('articles.show', $post) }}">Đọc tiếp</a>
          </div>
        </div>
      </article>
    @empty
      <div class="meta">Chưa có bài viết phù hợp.</div>
    @endforelse
  </div>
@endsection
