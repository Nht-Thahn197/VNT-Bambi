<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\LoadsBlogData;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    use LoadsBlogData;

    public function create()
    {
        return view('articles.create', $this->sharedData() + [
            'title' => 'BambiBlog · Viết bài',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbDir = public_path('uploads/thumbnails');
            File::ensureDirectoryExists($thumbDir);

            $file = $request->file('thumbnail');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($thumbDir, $filename);
            $thumbnailPath = '/uploads/thumbnails/'.$filename;
        }

        $article = Article::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'category_id' => (int) $data['category_id'],
            'status' => 1,
            'thumbnail' => $thumbnailPath,
            'user_id' => Auth::id(),
            'create_at' => now(),
        ]);

        $article->tags()->sync($data['tags'] ?? []);

        $redirect = $request->string('redirect')->toString();
        if ($redirect === 'home') {
            return redirect()
                ->route('home')
                ->with('success', 'Dang bai viet thanh cong.');
        }

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Dang bai viet thanh cong.');
    }

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

        $posts = $query->orderByDesc('create_at')->get();

        return view('articles.index', $this->sharedData() + [
            'title' => 'BambiBlog · Bài viết',
            'posts' => $posts,
            'filters' => [
                'q' => $request->string('q')->toString(),
                'category' => $request->integer('category'),
                'tag' => $request->integer('tag'),
            ],
        ]);
    }

    public function show(Article $article)
    {
        $article->load(['user', 'category', 'tags'])->loadCount('comments');

        $comments = Comment::with('user')
            ->where('article_id', $article->id)
            ->orderBy('create_at')
            ->get();

        return view('articles.show', $this->sharedData() + [
            'title' => $article->title,
            'post' => $article,
            'comments' => $comments,
        ]);
    }
}
