@extends('layouts.admin')

@section('content')
  <div class="content-header">
    <div>
      <h1>Sửa tag</h1>
      <div class="post-meta">Cập nhật tên tag.</div>
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
    <form class="form-stack" method="POST" action="{{ route('admin.tags.update', $tag) }}">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>Tên tag</label>
        <input
          class="input"
          type="text"
          name="name"
          value="{{ old('name', $tag->name) }}"
          required
          autofocus
        />
      </div>
      <button class="btn btn-primary" type="submit">Cập nhật</button>
    </form>
  </section>
@endsection
