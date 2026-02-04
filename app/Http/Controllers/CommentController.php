<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $data = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        Comment::create([
            'content' => $data['content'],
            'user_id' => Auth::id(),
            'article_id' => $article->id,
            'create_at' => now(),
        ]);

        return back()->with('success', 'Gui binh luan thanh cong.');
    }
}
