@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Trang cá nhân</h1>
      <div class="post-meta">Tổng hợp bài viết bạn đã đăng và đã chia sẻ.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('profile.edit') }}">Chỉnh sửa thông tin</a>
  </div>

  @if (session('success'))
    <div class="post-meta" style="color: #0f5132; font-weight: 600; margin-bottom: 12px;">
      {{ session('success') }}
    </div>
  @endif

  <section class="card" style="margin-bottom: 24px;">
    <div style="display: flex; gap: 16px; align-items: center;">
      <img class="avatar" src="{{ $user->avatar_url }}" alt="avatar" />
      <div>
        <div style="font-weight: 700; font-size: 18px;">{{ $user->user_name }}</div>
        <div class="meta">{{ $user->email }}</div>
      </div>
    </div>
  </section>

  <section style="display: grid; gap: 16px; margin-bottom: 28px;">
    <div class="content-header" style="margin-bottom: 0;">
      <h3>Bài đã đăng</h3>
      <a class="btn btn-outline" href="{{ route('articles.create') }}">Viết bài mới</a>
    </div>
    <div class="feed">
      @forelse ($my_posts as $post)
        <article class="card post-card" data-article-id="{{ $post->id }}">
          <div class="post-header">
            <div style="display: flex; gap: 12px; align-items: center;">
              <img class="avatar" src="{{ $post->user?->avatar_url ?? $user->avatar_url }}" alt="avatar" />
              <div>
                <div style="font-weight: 600;">{{ $post->user?->user_name ?? $user->user_name }}</div>
                <div class="post-meta">
                  {{ $post->create_at?->format('d M Y') ?? '---' }} · {{ $post->category?->name ?? 'Chưa phân loại' }}
                </div>
              </div>
            </div>
            <div class="user-menu post-menu" data-user-menu>
              <button
                class="user-menu__button post-menu__button"
                type="button"
                aria-haspopup="true"
                aria-expanded="false"
                aria-label="Tùy chọn bài viết"
              >
                <span class="user-menu__icon post-menu__icon" aria-hidden="true">
                  <span></span>
                  <span></span>
                  <span></span>
                </span>
              </button>
              <div class="user-menu__panel post-menu__panel" role="menu">
                <a href="{{ route('articles.edit', $post) }}" role="menuitem">Sửa bài viết</a>
                <form method="POST" action="{{ route('articles.destroy', $post) }}" role="none" onsubmit="return confirm('Xóa bài viết này?')">
                  @csrf
                  @method('DELETE')
                  <button class="user-menu__danger" type="submit" role="menuitem">Xóa bài viết</button>
                </form>
              </div>
            </div>
          </div>

          <h2 class="post-title">
            <a href="{{ route('articles.show', $post) }}">{{ $post->title }}</a>
          </h2>
          <div class="post-excerpt">{{ $post->excerpt }}</div>

          @if ($post->thumbnail_url)
            <img class="post-media" src="{{ $post->thumbnail_url }}" alt="thumbnail" />
          @endif

          <div class="post-meta">
            @foreach ($post->tags as $tag)
              <span class="tag">#{{ $tag->name }}</span>
            @endforeach
          </div>

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
      @empty
        <div class="meta">Bạn chưa đăng bài viết nào.</div>
      @endforelse
    </div>
  </section>

  <section style="display: grid; gap: 16px;">
    <div class="content-header" style="margin-bottom: 0;">
      <h3>Bài đã chia sẻ</h3>
    </div>
    <div class="feed">
      @forelse ($shared_posts as $post)
        <article class="card post-card" data-article-id="{{ $post->id }}">
          <div class="post-header">
            <div style="display: flex; gap: 12px; align-items: center;">
              <img class="avatar" src="{{ $post->user?->avatar_url ?? $user->avatar_url }}" alt="avatar" />
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

          @if ($post->thumbnail_url)
            <img class="post-media" src="{{ $post->thumbnail_url }}" alt="thumbnail" />
          @endif

          <div class="post-meta">
            @foreach ($post->tags as $tag)
              <span class="tag">#{{ $tag->name }}</span>
            @endforeach
          </div>

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
              <form method="POST" action="{{ route('articles.shares.destroy', $post) }}" onsubmit="return confirm('Bỏ chia sẻ bài viết này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Bỏ chia sẻ</button>
              </form>
            </div>
          </div>
        </article>
      @empty
        <div class="meta">Bạn chưa chia sẻ bài viết nào.</div>
      @endforelse
    </div>
  </section>
@endsection
