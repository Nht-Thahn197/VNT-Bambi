@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Cập nhật hồ sơ</h1>
      <div class="post-meta">Chỉnh sửa thông tin tài khoản của bạn.</div>
    </div>
    <a class="btn btn-outline" href="{{ route('profile.show') }}">Quay lại trang cá nhân</a>
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
    <form class="form-stack" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label>User name</label>
        <input
          class="input"
          type="text"
          name="user_name"
          value="{{ old('user_name', $user->user_name) }}"
          required
        />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input
          class="input"
          type="email"
          name="email"
          value="{{ old('email', $user->email) }}"
          required
        />
      </div>
      <div class="form-group">
        <label>Avatar (tùy chọn)</label>
        @php
          $previewAvatar = $user->avatar_url;
        @endphp
        <div class="avatar-upload">
          <a href="{{ $previewAvatar }}" target="_blank" rel="noopener" data-image-preview-link>
            <img id="profile-avatar-preview" class="image-preview" src="{{ $previewAvatar }}" alt="Avatar preview" />
          </a>
          <div>
            <div class="file-upload">
              <input
                class="file-input"
                id="profile-avatar-input"
                type="file"
                name="avatar"
                accept="image/*"
                data-image-preview-input
                data-image-preview-target="profile-avatar-preview"
                data-file-name-target="profile-avatar-name"
              />
              <label class="file-button" for="profile-avatar-input">Chọn ảnh</label>
              <span id="profile-avatar-name" class="file-name">Chưa chọn ảnh</span>
            </div>
            <div class="file-hint">PNG, JPG tối đa 2MB.</div>
          </div>
        </div>
      </div>
      <button class="btn btn-primary" type="submit">Cập nhật</button>
    </form>
  </section>
@endsection
