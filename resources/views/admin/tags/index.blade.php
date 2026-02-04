@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Tag</h1>
      <div class="post-meta">Quản lý tag bài viết.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.tags.create') }}">Thêm tag</a>
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
          <th>Tên</th>
          <th>Bài viết</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tags as $tag)
          <tr>
            <td>#{{ $tag->name }}</td>
            <td>{{ $tag->articles_count }}</td>
            <td>
              <div class="table-actions">
                <a class="btn btn-ghost" href="{{ route('admin.tags.edit', $tag) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Xóa</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3">Chưa có tag.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </section>
@endsection
