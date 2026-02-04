@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Thêm tag</h1>
      <div class="post-meta">Tạo tag mới cho bài viết.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('admin.tags.index') }}">Quay lại</a>
  </div>

  @if ($errors->any())
    <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <section class="card">
    <form class="form-stack" method="POST" action="{{ route('admin.tags.store') }}">
      @csrf
      <div class="form-group">
        <label>Tên tag</label>
        <input
          class="input"
          type="text"
          name="name"
          value="{{ old('name') }}"
          placeholder="Ví dụ: laravel"
          required
          autofocus
        />
      </div>
      <button class="btn btn-primary" type="submit">Lưu tag</button>
    </form>
  </section>
@endsection
