<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $posts = Article::with(['user', 'category'])
            ->withCount(['visibleComments as comments_count'])
            ->orderByDesc('create_at')
            ->limit(10)
            ->get();

        $categories = Category::withCount('articles')
            ->orderByDesc('articles_count')
            ->get();

        $tags = Tag::withCount('articles')
            ->orderByDesc('articles_count')
            ->get();

        $commentFilter = $request->string('comment_filter')->toString();
        $commentQuery = Comment::with(['user', 'article'])
            ->orderByDesc('create_at');

        if ($commentFilter === 'hidden') {
            $commentQuery->where('status', 0);
        }

        $recent_comments = $commentQuery->limit(6)->get();

        $stats = [
            'articles' => Article::count(),
            'articles_week' => Article::where('create_at', '>=', now()->subDays(7))->count(),
            'comments' => Comment::count(),
            'users' => User::count(),
            'active_users' => User::where('status', 1)->count(),
            'categories' => Category::count(),
        ];

        return view('admin.dashboard', [
            'title' => 'BambiBlog · Admin',
            'stats' => $stats,
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'recent_comments' => $recent_comments,
            'comment_filter' => $commentFilter,
        ]);
    }
}
