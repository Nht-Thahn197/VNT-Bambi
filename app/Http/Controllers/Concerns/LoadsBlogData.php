<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;

trait LoadsBlogData
{
    protected function sharedData(): array
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(12)
            ->get();

        $trending = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(3)
            ->get();

        $user = auth()->user() ?? User::orderBy('id')->first();

        $peopleQuery = User::withCount('articles')
            ->orderByDesc('articles_count');

        if ($user) {
            $peopleQuery->where('id', '!=', $user->id);
        }

        $people = $peopleQuery
            ->limit(3)
            ->get();

        $followingIds = $user ? $user->following()->pluck('users.id')->all() : [];

        $metrics = [
            'articles' => Article::count(),
            'articles_week' => Article::where('create_at', '>=', now()->subDays(7))->count(),
            'comments' => Comment::count(),
            'categories' => Category::count(),
        ];

        return compact('categories', 'tags', 'trending', 'people', 'user', 'metrics', 'followingIds');
    }
}
