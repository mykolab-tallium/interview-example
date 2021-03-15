<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index');
    }

    public function create()
    {
        $categories = Category::query()->latest('id')->limit(10)->get();
        return view('posts.add_edit_form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'banner' => 'image',
            'category_id' => 'required',
        ]);

        if (auth()->guest() || ! auth()->user()->hasRole('moderator')) {
            abort(403, 'Permission denied');
        }

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->category_id = $request->category_id;
        $post->author_id = auth()->id();
        $post->save();

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerName = $post->id . '.' . $banner->extension();
            Image::make($banner)
                ->resize(600, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(storage_path('app/public/posts/banner') . DIRECTORY_SEPARATOR . $bannerName);
            $post->banner = $bannerName;
            $post->save();
        }

        return redirect('/posts')->with('message', 'Post successfully created.');
    }
}
