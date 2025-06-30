<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::with('user')->latest()->get(); // include user info

        return view('posts.index', compact('posts'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:500',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:10240', // up to 10MB
        ]);

        $imagePath = null;
        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('uploads', 'public');
        }
        $request->user()->posts()->create([
            'content' => $request->content,
            'image_path' => $mediaPath,
        ]);



        return redirect()->route('posts.index');
    }
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        $request->validate([
            'content' => 'nullable|string|max:500',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi,webm|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            $post->image_path = $request->file('image')->store('uploads', 'public');
        }

        $post->content = $request->content;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403); // Forbidden
        }

        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}
