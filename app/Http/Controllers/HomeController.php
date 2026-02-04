<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\LoadsBlogData;
use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use LoadsBlogData;

    public function index(Request $request)
    {
        $query = Article::with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->where('status', 1);

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();
            $query->where(function ($sub) use ($keyword) {
                $sub->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('tag')) {
            $tagId = $request->integer('tag');
            $query->whereHas('tags', function ($sub) use ($tagId) {
                $sub->where('tags.id', $tagId);
            });
        }

        if ($request->string('feed')->toString() === 'following') {
            $followingIds = $request->user()?->following()->pluck('users.id') ?? collect();
            $query->whereIn('user_id', $followingIds->isEmpty() ? [0] : $followingIds);
        }

        $posts = $query->orderByDesc('create_at')
            ->limit(10)
            ->get();

        return view('home', $this->sharedData() + [
            'title' => 'BambiBlog · Trang chủ',
            'posts' => $posts,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'category' => $request->integer('category'),
                'tag' => $request->integer('tag'),
                'feed' => $request->string('feed')->toString(),
            ],
        ]);
    }
}
