<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;

class AdminCommentController extends Controller
{
    public function hide(Comment $comment): RedirectResponse
    {
        $comment->status = 0;
        $comment->save();

        return back()->with('success', 'Da an binh luan.');
    }

    public function unhide(Comment $comment): RedirectResponse
    {
        $comment->status = 1;
        $comment->save();

        return back()->with('success', 'Da hien thi binh luan.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Da xoa binh luan.');
    }
}
