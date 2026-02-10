@extends('layouts.auth')

@section('content')
  <h3>Tạo tài khoản</h3>
  @if ($errors->any())
    <div class="meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif
  <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label>Họ Tên</label>
      <input
        class="input"
        type="text"
        name="user_name"
        value="{{ old('user_name') }}"
        placeholder="Nhập user name"
        required
        autofocus
      />
    </div>
    <div class="form-group">
      <label>Email</label>
      <input
        class="input"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="you@example.com"
        required
      />
    </div>
    <div class="form-group">
      <label>Mật khẩu</label>
      <input
        class="input"
        type="password"
        name="password"
        placeholder="••••••••"
        required
      />
    </div>
    <div class="form-group">
      <label>Avatar</label>
      @php
        $defaultAvatar = asset('images/avatar/default-avatar.png');
      @endphp
      <div class="avatar-upload">
        <a href="{{ $defaultAvatar }}" target="_blank" rel="noopener" data-image-preview-link>
          <img id="register-avatar-preview" class="image-preview" src="{{ $defaultAvatar }}" alt="Avatar preview" />
        </a>
        <div>
          <div class="file-upload">
            <input
              class="file-input"
              id="register-avatar-input"
              type="file"
              name="avatar"
              accept="image/*"
              data-image-preview-input
              data-image-preview-target="register-avatar-preview"
              data-file-name-target="register-avatar-name"
            />
            <label class="file-button" for="register-avatar-input">Chọn ảnh</label>
            <span id="register-avatar-name" class="file-name">Chưa chọn ảnh</span>
          </div>
          <div class="file-hint">PNG, JPG tối đa 2MB.</div>
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">Đăng ký</button>
    <div class="meta">
      Đã có tài khoản?
      <a href="{{ url('/login') }}" style="color: var(--brand); font-weight: 600;">Đăng nhập</a>
    </div>
  </form>
@endsection
