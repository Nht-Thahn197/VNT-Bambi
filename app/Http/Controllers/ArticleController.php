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
            $thumbDir = public_path('images/thumbnail');
            File::ensureDirectoryExists($thumbDir);

            $file = $request->file('thumbnail');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($thumbDir, $filename);
            $thumbnailPath = '/images/thumbnail/'.$filename;
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

    public function edit(Article $article)
    {
        $this->ensureOwner($article);
        $article->load('tags');

        return view('articles.edit', $this->sharedData() + [
            'title' => 'BambiBlog · Sửa bài viết',
            'post' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $this->ensureOwner($article);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $thumbnailPath = $article->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $thumbDir = public_path('images/thumbnail');
            File::ensureDirectoryExists($thumbDir);

            $file = $request->file('thumbnail');
            $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
            $file->move($thumbDir, $filename);
            $thumbnailPath = '/images/thumbnail/'.$filename;

            if ($article->thumbnail) {
                $normalized = ltrim($article->thumbnail, '/');
                if (
                    str_starts_with($normalized, 'uploads/thumbnails/')
                    || str_starts_with($normalized, 'images/thumbnail/')
                ) {
                    $oldPath = public_path($normalized);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }
        }

        $article->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'category_id' => (int) $data['category_id'],
            'thumbnail' => $thumbnailPath,
        ]);

        $article->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('articles.show', $article)
            ->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(Article $article)
    {
        $this->ensureOwner($article);

        if ($article->thumbnail) {
            $normalized = ltrim($article->thumbnail, '/');
            if (
                str_starts_with($normalized, 'uploads/thumbnails/')
                || str_starts_with($normalized, 'images/thumbnail/')
            ) {
                $thumbPath = public_path($normalized);
                if (File::exists($thumbPath)) {
                    File::delete($thumbPath);
                }
            }
        }

        $article->comments()->delete();
        $article->tags()->detach();
        $article->likes()->detach();
        $article->shares()->detach();
        $article->delete();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Xóa bài viết thành công.');
    }

    public function index(Request $request)
    {
        $userId = $request->user()?->id ?? 0;

        $query = Article::with(['user', 'category', 'tags'])
            ->withCount(['comments', 'likes', 'shares'])
            ->withCount([
                'likes as liked_by_me' => function ($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                },
                'shares as shared_by_me' => function ($sub) use ($userId) {
                    $sub->where('user_id', $userId);
                },
            ])
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
        $userId = request()->user()?->id ?? 0;

        $article->load(['user', 'category', 'tags'])->loadCount([
            'comments',
            'likes',
            'shares',
            'likes as liked_by_me' => function ($sub) use ($userId) {
                $sub->where('user_id', $userId);
            },
            'shares as shared_by_me' => function ($sub) use ($userId) {
                $sub->where('user_id', $userId);
            },
        ]);

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

    private function ensureOwner(Article $article): void
    {
        $userId = Auth::id();
        if (! $userId || $article->user_id !== $userId) {
            abort(403);
        }
    }
}
