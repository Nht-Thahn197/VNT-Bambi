@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Danh mục</h1>
      <div class="post-meta">Quản lý danh mục bài viết.</div>
    </div>
    <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">Thêm danh mục</a>
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
          <th>Mô tả</th>
          <th>Bài viết</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($categories as $category)
          <tr>
            <td>{{ $category->name }}</td>
            <td>{{ $category->description ?? '---' }}</td>
            <td>{{ $category->articles_count }}</td>
            <td>
              <div class="table-actions">
                <a class="btn btn-ghost" href="{{ route('admin.categories.edit', $category) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit">Xóa</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4">Chưa có danh mục.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </section>
@endsection
