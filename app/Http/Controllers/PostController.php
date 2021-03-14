<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'banner' => 'image',
            'published' => 'required|boolean',
            'category_id' => 'required',
        ]);

        if (auth()->guest() || auth()->user()->hasRole('moderator')) {
            return redirect('/posts')->with('error', 'Permission denied');
        }

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->published = $request->published;
        $post->category_id = $request->category_id;
        $post->author_id = auth()->id();
        $post->save();

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            Image::make($banner)
                ->resize(600, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app/public/posts/banner') . DIRECTORY_SEPARATOR . $post->id . '.' . $banner->extension());
        }

        return redirect('/posts')->with('message', 'Post successfully created.');
    }
}
