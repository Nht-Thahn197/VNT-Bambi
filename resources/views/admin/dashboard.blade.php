@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Xin chào, Admin</h1>
      <div class="post-meta">Tổng quan hoạt động cộng đồng trong tuần.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.articles.create') }}">Tạo bài viết mới</a>
  </div>

  <div class="stats-grid" id="stats">
    <div class="stat-card">
      <div class="post-meta">Bài viết</div>
      <h3>{{ $stats['articles'] }}</h3>
      <div class="post-meta">+{{ $stats['articles_week'] }} trong 7 ngày</div>
    </div>
    <div class="stat-card">
      <div class="post-meta">Bình luận</div>
      <h3>{{ $stats['comments'] }}</h3>
      <div class="post-meta">Tổng số bình luận trên hệ thống</div>
    </div>
    <div class="stat-card">
      <div class="post-meta">Người dùng</div>
      <h3>{{ $stats['users'] }}</h3>
      <div class="post-meta">Đang hoạt động {{ $stats['active_users'] }}</div>
    </div>
    <div class="stat-card">
      <div class="post-meta">Danh mục</div>
      <h3>{{ $stats['categories'] }}</h3>
      <div class="post-meta">Tổng số danh mục</div>
    </div>
  </div>

  <section class="card" id="articles">
    <div class="content-header">
      <h3>Quản lý bài viết</h3>
      <a class="btn btn-outline" href="{{ route('admin.articles.create') }}">Thêm bài viết</a>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Chuyên mục</th>
          <th>Tác giả</th>
          <th>Trạng thái</th>
          <th>Bình luận</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($posts as $post)
          <tr>
            <td>{{ $post->title }}</td>
            <td>{{ $post->category?->name ?? 'Chưa phân loại' }}</td>
            <td>{{ $post->user?->user_name ?? 'Ẩn danh' }}</td>
            <td>{{ $post->status_label }}</td>
            <td>{{ $post->comments_count }}</td>
            <td>
              <div class="table-actions">
                <a class="btn btn-ghost" href="{{ route('admin.articles.edit', $post) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.articles.destroy', $post) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Xóa</button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>

  <section class="card" id="categories" style="margin-top: 24px;">
    <div class="content-header">
      <h3>Danh mục & Tag</h3>
      <a class="btn btn-outline" href="{{ route('admin.categories.create') }}">Thêm danh mục</a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
      @foreach ($categories as $category)
        <div class="card" style="box-shadow: none; border: 1px dashed var(--line);">
          <h4>{{ $category->name }}</h4>
          <div class="post-meta">{{ $category->description }}</div>
          <div class="post-meta">Bài viết: {{ $category->articles_count }}</div>
        </div>
      @endforeach
    </div>
    <div style="margin-top: 16px;">
      <h4>Tag</h4>
      <div class="tag-cloud">
        @foreach ($tags as $tag)
          <span class="tag">#{{ $tag->name }} ({{ $tag->articles_count }})</span>
        @endforeach
      </div>
    </div>
  </section>

  <section class="card" id="comments" style="margin-top: 24px;">
    <div class="content-header">
      <h3>Quản lý bình luận</h3>
      <button class="btn btn-outline">Lọc vi phạm</button>
    </div>
    <div class="comment-list">
      @foreach ($recent_comments as $comment)
        <div class="comment">
          <img class="avatar" src="{{ $comment->user?->avatar_url ?? 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80' }}" alt="avatar" />
          <div class="comment-content">
            <div class="name">{{ $comment->user?->user_name ?? 'Ẩn danh' }}</div>
            <div class="meta">
              {{ $comment->create_at?->format('d M Y') ?? '---' }} · {{ $comment->article?->title ?? 'Bài viết' }}
            </div>
            <div>{{ $comment->content }}</div>
          </div>
          <div class="table-actions">
            <button class="btn btn-ghost">Ẩn</button>
            <button class="btn btn-danger">Xóa</button>
          </div>
        </div>
      @endforeach
    </div>
  </section>
@endsection
