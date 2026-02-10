<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userId = $user?->id ?? 0;

        $baseQuery = Article::with(['user', 'category', 'tags'])
            ->withCount(['visibleComments as comments_count', 'likes', 'shares'])
            ->withCount([
                'likes as liked_by_me' => function ($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                },
                'shares as shared_by_me' => function ($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                },
            ]);

        $myPosts = (clone $baseQuery)
            ->where('user_id', $userId)
            ->orderByDesc('create_at')
            ->get();

        $sharedPosts = $user
            ? $user->sharedArticles()
                ->with(['user', 'category', 'tags'])
                ->withCount(['visibleComments as comments_count', 'likes', 'shares'])
                ->withCount([
                    'likes as liked_by_me' => function ($sub) use ($userId) {
                        $sub->where('user_id', $userId);
                    },
                    'shares as shared_by_me' => function ($sub) use ($userId) {
                        $sub->where('user_id', $userId);
                    },
                ])
                ->orderByDesc('create_at')
                ->get()
            : collect();

        return view('profile', [
            'title' => 'BambiBlog · Trang cá nhân',
            'user' => $user,
            'my_posts' => $myPosts,
            'shared_posts' => $sharedPosts,
        ]);
    }

    public function edit()
    {
        return view('profile-edit', [
            'title' => 'BambiBlog · Cập nhật hồ sơ',
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'user_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $avatarDir = public_path('images/avatar');
            File::ensureDirectoryExists($avatarDir);

            $file = $request->file('avatar');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $data['avatar'] = 'images/avatar/'.$filename;

            if ($user->avatar) {
                $normalized = ltrim($user->avatar, '/');
                if (
                    str_starts_with($normalized, 'uploads/avatars/')
                    || str_starts_with($normalized, 'images/avatar/')
                ) {
                    $oldPath = public_path($normalized);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }
}
