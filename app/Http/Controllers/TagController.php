<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('articles')
            ->orderByDesc('id')
            ->get();

        return view('admin.tags.index', [
            'title' => 'BambiBlog · Tag',
            'tags' => $tags,
        ]);
    }

    public function create()
    {
        return view('admin.tags.create', [
            'title' => 'BambiBlog · Tạo tag',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:tags,name'],
        ]);

        Tag::create($data);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tạo tag thành công.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', [
            'title' => 'BambiBlog · Sửa tag',
            'tag' => $tag,
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags', 'name')->ignore($tag->id),
            ],
        ]);

        $tag->update($data);

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Cập nhật tag thành công.');
    }

    public function destroy(Tag $tag)
    {
        $tag->articles()->detach();
        $tag->delete();

        return back()->with('success', 'Xóa tag thành công.');
    }
}
