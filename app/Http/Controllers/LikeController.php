<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $user->likedArticles()->syncWithoutDetaching([$article->id]);

        if ($request->expectsJson()) {
            $article->loadCount('likes');

            return response()->json([
                'liked' => true,
                'likes_count' => $article->likes_count,
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

        $user->likedArticles()->detach($article->id);

        if ($request->expectsJson()) {
            $article->loadCount('likes');

            return response()->json([
                'liked' => false,
                'likes_count' => $article->likes_count,
            ]);
        }

        return back();
    }
}
