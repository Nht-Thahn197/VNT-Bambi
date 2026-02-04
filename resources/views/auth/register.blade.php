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
      <label>Avatar (tùy chọn)</label>
      @php
        $defaultAvatar = 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=120&h=120&q=80';
      @endphp
      <a href="{{ $defaultAvatar }}" target="_blank" rel="noopener" data-image-preview-link>
        <img id="register-avatar-preview" class="image-preview" src="{{ $defaultAvatar }}" alt="Avatar preview" />
      </a>
      <input
        class="input"
        type="file"
        name="avatar"
        accept="image/*"
        data-image-preview-input
        data-image-preview-target="register-avatar-preview"
      />
    </div>
    <button class="btn btn-primary" type="submit">Đăng ký</button>
    <div class="meta">
      Đã có tài khoản?
      <a href="{{ url('/login') }}" style="color: var(--brand); font-weight: 600;">Đăng nhập</a>
    </div>
  </form>
@endsection
