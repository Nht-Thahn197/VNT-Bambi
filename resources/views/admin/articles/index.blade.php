@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Bài viết</h1>
      <div class="post-meta">Quản lý bài viết trên hệ thống.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.articles.create') }}">Thêm bài viết</a>
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

  <section class="card">
    <table class="table">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Chuyên mục</th>
          <th>Tác giả</th>
          <th>Trạng thái</th>
          <th>Bình luận</th>
          <th>Ngày</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($articles as $article)
          <tr>
            <td>{{ $article->title }}</td>
            <td>{{ $article->category?->name ?? '---' }}</td>
            <td>{{ $article->user?->user_name ?? '---' }}</td>
            <td>{{ $article->status_label }}</td>
            <td>{{ $article->comments_count }}</td>
            <td>{{ $article->create_at?->format('d/m/Y') ?? '---' }}</td>
            <td>
              <div class="table-actions">
                <a class="btn btn-ghost" href="{{ route('admin.articles.edit', $article) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Xóa</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7">Chưa có bài viết.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </section>
@endsection
