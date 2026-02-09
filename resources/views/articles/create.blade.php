@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Viết bài mới</h1>
      <div class="post-meta">Chia sẻ bài viết của bạn với cộng đồng.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('articles.index') }}">Quay lại</a>
  </div>

  @if ($errors->any())
    <div class="post-meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <section class="card">
    <form class="form-stack form-stack--loose" method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label>Tiêu đề</label>
        <input
          class="input"
          type="text"
          name="title"
          value="{{ old('title') }}"
          placeholder="Nhap tieu de bai viet"
          required
          autofocus
        />
      </div>
      <div class="form-group">
        <label>Chuyên mục</label>
        <select class="input" name="category_id" required>
          <option value="">Chọn chuyên mục</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
              {{ $category->name }}
            </option>
          @endforeach
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
                @checked(in_array($tag->id, old('tags', [])))
              />
              <span>#{{ $tag->name }}</span>
            </label>
          @empty
            <div class="post-meta">Chưa có tag.</div>
          @endforelse
        </div>
      </div>
      <div class="form-group">
        <label>Thumbnail (tùy chọn)</label>
        @php
          $previewThumbnail = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
        @endphp
        <div class="avatar-upload">
          <a href="{{ $previewThumbnail }}" target="_blank" rel="noopener" data-image-preview-link>
            <img
              id="article-thumbnail-preview"
              class="image-preview"
              src="{{ $previewThumbnail }}"
              alt="Thumbnail preview"
            />
          </a>
          <div>
            <div class="file-upload">
              <input
                class="file-input"
                id="article-thumbnail-input"
                type="file"
                name="thumbnail"
                accept="image/*"
                data-image-preview-input
                data-image-preview-target="article-thumbnail-preview"
                data-file-name-target="article-thumbnail-name"
              />
              <label class="file-button" for="article-thumbnail-input">Chọn ảnh</label>
              <span id="article-thumbnail-name" class="file-name">Chưa chọn ảnh</span>
            </div>
            <div class="file-hint">PNG, JPG tối đa 4MB.</div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Nội dung</label>
        <textarea
          class="input"
          name="content"
          rows="10"
          placeholder="Nhập nội dung bài viết"
          required
        >{{ old('content') }}</textarea>
      </div>
      <button class="btn btn-primary" type="submit">Đăng bài</button>
    </form>
  </section>
@endsection
