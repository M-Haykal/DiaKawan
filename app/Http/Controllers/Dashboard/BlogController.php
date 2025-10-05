<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->simplePaginate(6);
        return view('dashboard.pages.blog', compact('blogs'));
    }

    public function create()
    {
        return view('dashboard.action.blog.create_blog');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ]);

        try {
            $path = $request->file('image')->store('blogs', 'public');

            $blog = Blog::create([
                'image' => $path,
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'],
                'content' => $validated['content'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('blogs.index')->with('success', 'Blog berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Blog creation error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('dashboard.action.blog.detail_blog', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ]);

        $updateData = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::exists('public/' . $blog->image)) {
                Storage::delete('public/' . $blog->image);
            }

            $path = $request->file('image')->store('blogs', 'public');
            $updateData['image'] = $path;
        }
        $blog->update($updateData);
        return redirect()->route('blogs.index')->with('success', 'Blog berhasil diupdate!');
    }
}
