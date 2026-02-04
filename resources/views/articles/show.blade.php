@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>{{ $post->title }}</h1>
      <div class="post-meta">
        {{ $post->create_at?->format('d M Y') ?? '---' }} · {{ $post->category?->name ?? 'Chưa phân loại' }} · {{ $post->reading_time }}
      </div>
    </div>
    <button class="btn btn-outline">Lưu bài</button>
  </div>

  <article class="card post-card">
    <div class="post-header">
      <div style="display: flex; gap: 12px; align-items: center;">
        <img class="avatar" src="{{ $post->user?->avatar_url ?? 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80' }}" alt="avatar" />
        <div>
          <div style="font-weight: 600;">{{ $post->user?->user_name ?? 'Ẩn danh' }}</div>
          <div class="post-meta">Đăng ngày {{ $post->create_at?->format('d M Y') ?? '---' }}</div>
        </div>
      </div>
      <div class="actions-right">
        <span class="stat">Bình luận: {{ $post->comments_count }}</span>
      </div>
    </div>

    @if ($post->thumbnail)
      <img class="post-media" src="{{ $post->thumbnail }}" alt="thumbnail" />
    @endif

    <div class="article-body">
      @foreach (preg_split("/\n\n/", $post->content ?? '') as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>

    <div class="post-meta">
      @foreach ($post->tags as $tag)
        <span class="tag">#{{ $tag->name }}</span>
      @endforeach
    </div>
  </article>

  <div class="card">
    <h3>Binh luan ({{ $comments->count() }})</h3>
    @if (session('success'))
      <div class="post-meta" style="color: #0f5132; font-weight: 600; margin-bottom: 12px;">
        {{ session('success') }}
      </div>
    @endif
    <div class="comment-list">
      @forelse ($comments as $comment)
        <div class="comment">
          <img class="avatar" src="{{ $comment->user?->avatar_url ?? 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80' }}" alt="avatar" />
          <div class="comment-content">
            <div class="name">{{ $comment->user?->user_name ?? 'Ẩn danh' }}</div>
            <div class="meta">{{ $comment->create_at?->format('d M Y') ?? '---' }}</div>
            <div>{{ $comment->content }}</div>
          </div>
        </div>
      @empty
        <div class="meta">Chưa có bình luận.</div>
      @endforelse
    </div>
  </div>

  <div class="card">
    <h3>Thêm bình luận</h3>
    @if ($errors->any())
      <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('articles.comments.store', $post) }}">
      @csrf
      <div class="form-group">
        <label>Nội dung bình luận</label>
        <textarea
          class="textarea"
          name="content"
          rows="4"
          placeholder="Chia sẻ suy nghĩ của bạn..."
          required
        >{{ old('content') }}</textarea>
      </div>
      <button class="btn btn-primary" type="submit">Gửi bình luận</button>
    </form>
  </div>
@endsection
