@extends('layouts.admin')

@section('content')
  @php
    $selectedTags = old('tags', $article->tags->pluck('id')->all());
  @endphp

  <div class="content-header">
    <div>
      <h1>Sửa bài viết</h1>
      <div class="post-meta">Cập nhật nội dung bài viết.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('admin.articles.index') }}">Quay lại</a>
  </div>

  @if ($errors->any())
    <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <section class="card">
    <form class="form-stack" method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>Tiêu đề</label>
        <input
          class="input"
          type="text"
          name="title"
          value="{{ old('title', $article->title) }}"
          required
          autofocus
        />
      </div>
      <div class="form-group">
        <label>Chuyên mục</label>
        <select class="input" name="category_id" required>
          <option value="">Chọn chuyên mục</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $article->category_id) == $category->id)>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Trạng thái</label>
        <select class="input" name="status" required>
          <option value="1" @selected(old('status', $article->status) == 1)>Xuất bản</option>
          <option value="0" @selected(old('status', $article->status) == 0)>Nháp</option>
        </select>
      </div>
      <div class="form-group">
        <label>Tag</label>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
          @forelse ($tags as $tag)
            <label style="display: flex; align-items: center; gap: 6px;">
              <input
                type="checkbox"
                name="tags[]"
                value="{{ $tag->id }}"
                @checked(in_array($tag->id, $selectedTags))
              />
              <span>#{{ $tag->name }}</span>
            </label>
          @empty
            <div class="post-meta">Chưa có tag.</div>
          @endforelse
        </div>
      </div>
      <div class="form-group">
        <label>Thumbnail</label>
        <input class="input" type="file" name="thumbnail" accept="image/*" />
        @if ($article->thumbnail)
          <div class="post-meta" style="margin-top: 6px;">
            Hiện tại: {{ $article->thumbnail }}
          </div>
        @endif
      </div>
      <div class="form-group">
        <label>Nội dung</label>
        <textarea class="input" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
      </div>
      <button class="btn btn-primary" type="submit">Cập nhật</button>
    </form>
  </section>
@endsection
