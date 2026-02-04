@extends('layouts.auth')

@section('content')
  <h3>Đăng nhập</h3>
  @if (session('success'))
    <div class="meta" style="color: #0f5132; font-weight: 600; margin-bottom: 12px;">
      {{ session('success') }}
    </div>
  @endif
  @if ($errors->any())
    <div class="meta" style="color: #b42318; font-weight: 600; margin-bottom: 12px;">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif
  <form method="POST" action="{{ route('login.submit') }}">
    @csrf
    <div class="form-group">
      <label>Email</label>
      <input
        class="input"
        type="email"
        name="email"
        value="{{ old('email') }}"
        placeholder="you@example.com"
        required
        autofocus
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
    <button class="btn btn-primary" type="submit">Đăng nhập</button>
    <div class="meta">
      Chưa có tài khoản?
      <a href="{{ url('/register') }}" style="color: var(--brand); font-weight: 600;">Đăng ký ngay</a>
    </div>
  </form>
@endsection
