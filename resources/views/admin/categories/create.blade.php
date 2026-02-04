@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Thêm danh mục</h1>
      <div class="post-meta">Tạo danh mục mới cho bài viết.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('admin.categories.index') }}">Quay lại</a>
  </div>

  @if ($errors->any())
    <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <section class="card">
    <form class="form-stack" method="POST" action="{{ route('admin.categories.store') }}">
      @csrf
      <div class="form-group">
        <label>Tên danh mục</label>
        <input
          class="input"
          type="text"
          name="name"
          value="{{ old('name') }}"
          placeholder="Ví dụ: Công nghệ"
          required
          autofocus
        />
      </div>
      <div class="form-group">
        <label>Mô tả</label>
        <textarea class="input" name="description" rows="4" placeholder="Mô tả ngắn">{{ old('description') }}</textarea>
      </div>
      <button class="btn btn-primary" type="submit">Lưu danh mục</button>
    </form>
  </section>
@endsection
