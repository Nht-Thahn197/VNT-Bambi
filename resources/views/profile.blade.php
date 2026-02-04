@extends('layouts.app')

@section('content')
  <div class="content-header">
    <div>
      <h1>Trang cá nhân</h1>
      <div class="post-meta">Cập nhật thông tin tài khoản của bạn.</div>
    </div>
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
        <a href="{{ $previewAvatar }}" target="_blank" rel="noopener" data-image-preview-link>
          <img id="profile-avatar-preview" class="image-preview" src="{{ $previewAvatar }}" alt="Avatar preview" />
        </a>
        <input
          class="input"
          type="file"
          name="avatar"
          accept="image/*"
          data-image-preview-input
          data-image-preview-target="profile-avatar-preview"
        />
      </div>
      <button class="btn btn-primary" type="submit">Cập nhật</button>
    </form>
  </section>
@endsection
