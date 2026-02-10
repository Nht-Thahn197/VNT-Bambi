<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['user', 'category', 'tags'])
            ->withCount(['visibleComments as comments_count'])
            ->orderByDesc('create_at')
            ->get();

        return view('admin.articles.index', [
            'title' => 'BambiBlog · Bài viết',
            'articles' => $articles,
        ]);
    }

    public function create()
    {
        return view('admin.articles.create', [
            'title' => 'BambiBlog · Thêm bài viết',
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in([0, 1])],
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
            'status' => (int) $data['status'],
            'thumbnail' => $thumbnailPath,
            'user_id' => Auth::id(),
            'create_at' => now(),
        ]);

        $article->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Tạo bài viết thành công.');
    }

    public function edit(Article $article)
    {
        $article->load('tags');

        return view('admin.articles.edit', [
            'title' => 'BambiBlog · Sửa bài viết',
            'article' => $article,
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in([0, 1])],
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
            'status' => (int) $data['status'],
            'thumbnail' => $thumbnailPath,
        ]);

        $article->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(Article $article)
    {
        $article->tags()->detach();
        $article->comments()->delete();

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

        $article->delete();

        return back()->with('success', 'Xóa bài viết thành công.');
    }
}
