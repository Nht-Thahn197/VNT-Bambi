@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Sửa danh mục</h1>
      <div class="post-meta">Cập nhật thông tin danh mục.</div>
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
    <form class="form-stack" method="POST" action="{{ route('admin.categories.update', $category) }}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>Tên danh mục</label>
        <input
          class="input"
          type="text"
          name="name"
          value="{{ old('name', $category->name) }}"
          required
          autofocus
        />
      </div>
      <div class="form-group">
        <label>Mô tả</label>
        <textarea class="input" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
      </div>
      <button class="btn btn-primary" type="submit">Cập nhật</button>
    </form>
  </section>
@endsection
