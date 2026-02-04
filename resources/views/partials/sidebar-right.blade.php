<div class="card">
  <h3>Xu hướng</h3>
  <ul class="menu-list">
    @forelse ($trending ?? [] as $tag)
      <li>
        <a href="{{ route('articles.index', ['tag' => $tag->id]) }}">
          #{{ $tag->name }}
          <span>{{ $tag->articles_count }} bài</span>
        </a>
      </li>
    @empty
      <li><span class="meta">Chưa có dữ liệu</span></li>
    @endforelse
  </ul>
</div>

<div class="card">
  <h3>Gợi ý kết nối</h3>
  <div style="display: grid; gap: 12px;">
    @php
      $currentUser = auth()->user();
      $followingIds = $followingIds ?? [];
    @endphp
    @foreach ($people ?? [] as $person)
      <div style="display: flex; align-items: center; gap: 10px;">
        <img class="avatar" src="{{ $person->avatar_url }}" alt="avatar" />
        <div style="flex: 1;">
          <div style="font-weight: 600;">{{ $person->user_name ?? 'Thành viên' }}</div>
          <div class="meta">{{ $person->role ?? 'Tác giả' }}</div>
        </div>
        @if ($currentUser && $currentUser->id !== $person->id)
          @if (in_array($person->id, $followingIds))
            <form method="POST" action="{{ route('users.follow.destroy', $person) }}">
              @csrf
              @method('DELETE')
              <button class="btn btn-ghost" type="submit">Đang theo dõi</button>
            </form>
          @else
            <form method="POST" action="{{ route('users.follow.store', $person) }}">
              @csrf
              <button class="btn btn-outline" type="submit">Theo dõi</button>
            </form>
          @endif
        @endif
      </div>
    @endforeach
  </div>
</div>

<div class="card">
  <h3>Bản tin mới</h3>
  <p class="meta">Nhận email tổng hợp bài viết nổi bật mỗi tuần.</p>
  <div class="newsletter-form">
    <input class="input" type="email" placeholder="Email của bạn" />
    <button class="btn btn-primary">Đăng ký</button>
  </div>
</div>
