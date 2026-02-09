<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->sharedArticles()->syncWithoutDetaching([$article->id]);
        $article->loadCount('shares');

        if ($request->expectsJson()) {
            return response()->json([
                'shares_count' => $article->shares_count,
            ]);
        }

        return back();
    }

    public function destroy(Request $request, Article $article)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->sharedArticles()->detach($article->id);
        $article->loadCount('shares');

        if ($request->expectsJson()) {
            return response()->json([
                'shares_count' => $article->shares_count,
                'shared' => false,
            ]);
        }

        return back()->with('success', 'Đã bỏ chia sẻ bài viết.');
    }
}
