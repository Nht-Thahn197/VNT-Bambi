@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Danh sách bài viết</h1>
      <div class="post-meta">Lọc theo chuyên mục và tag bạn quan tâm.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('articles.create') }}">Viết bài mới</a>
  </div>

  <form class="card filter-bar" method="GET" action="{{ route('articles.index') }}">
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
    <button class="btn btn-outline" type="submit">Lọc</button>
  </form>

  <div class="feed">
    @foreach ($posts as $post)
      <article class="card post-card" data-article-id="{{ $post->id }}">
        <div class="post-header">
          <div style="display: flex; gap: 12px; align-items: center;">
            <img class="avatar" src="{{ $post->user?->avatar_url ?? asset('images/avatar/default-avatar.png') }}" alt="avatar" />
            <div>
              <div style="font-weight: 600;">{{ $post->user?->user_name ?? 'Ẩn danh' }}</div>
              <div class="post-meta">{{ $post->create_at?->format('d M Y') ?? '---' }} · {{ $post->category?->name ?? 'Chưa phân loại' }}</div>
            </div>
          </div>
          <span class="pill">{{ $post->reading_time }}</span>
        </div>

        <h2 class="post-title">
          <a href="{{ route('articles.show', $post) }}">{{ $post->title }}</a>
        </h2>
        <div class="post-excerpt">{{ $post->excerpt }}</div>

        <div class="post-actions">
          <div class="actions-left">
            <span class="stat">Bình luận: {{ $post->comments_count }}</span>
            <span class="stat">Thích: <span data-like-count>{{ $post->likes_count }}</span></span>
            <span class="stat">Chia sẻ: <span data-share-count>{{ $post->shares_count }}</span></span>
          </div>
          <div class="actions-right">
            <button
              class="btn btn-ghost post-action-btn js-like-btn @if ($post->liked_by_me) is-active @endif"
              type="button"
              aria-pressed="{{ $post->liked_by_me ? 'true' : 'false' }}"
              data-liked="{{ $post->liked_by_me ? '1' : '0' }}"
              data-like-url="{{ route('articles.likes.store', $post) }}"
              data-unlike-url="{{ route('articles.likes.destroy', $post) }}"
            >
              {{ $post->liked_by_me ? 'Đã thích' : 'Ưa thích' }}
            </button>
            <button
              class="btn btn-ghost post-action-btn js-share-btn @if ($post->shared_by_me) is-active @endif"
              type="button"
              data-shared="{{ $post->shared_by_me ? '1' : '0' }}"
              data-share-url="{{ route('articles.shares.store', $post) }}"
              data-share-link="{{ route('articles.show', $post) }}"
              data-share-title="{{ $post->title }}"
            >
              {{ $post->shared_by_me ? 'Đã chia sẻ' : 'Chia sẻ' }}
            </button>
            <a class="btn btn-outline" href="{{ route('articles.show', $post) }}">Đọc tiếp</a>
          </div>
        </div>
      </article>
    @endforeach
  </div>
@endsection
