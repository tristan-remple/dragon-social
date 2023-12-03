<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all posts in chronological order
        // return that data with the index view
        $posts = Post::orderBy('created_at', 'desc')->with('creator')->get();
        $moderator = Role::where('name', 'Moderator')->first();
        return(view('posts.index', compact(['posts', 'moderator'])));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => [
                File::image()
            ]
        ]);

        $newPost = new Post();

        $newPost->title = $request->title;
        $newPost->content = $request->content;
        if ($request->alt) {
            $newPost->alt = $request->alt;
        }
        $newPost->created_by = Auth::id();

        // https://codesource.io/complete-laravel-8-image-upload-tutorial-with-example/
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').'_'.$file->getClientOriginalName();
            $file-> move(public_path('storage/img'), $filename);
            $newPost['img_filename']= $filename;
        }

        $newPost->save();

        return redirect(route('posts.index'))->with('status', 'Post added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $posts = [$post];
        $moderator = Role::where('name', 'Moderator')->first();
        return(view('posts.index', compact(['posts', 'moderator'])));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts/edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => [
                File::image()
            ]
        ]);

        $post->title = $request->title;
        $post->content = $request->content;
        if ($request->alt) {
            $post->alt = $request->alt;
        }
        $post->created_by = Auth::id();

        // https://codesource.io/complete-laravel-8-image-upload-tutorial-with-example/
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').'_'.$file->getClientOriginalName();
            $file-> move(public_path('storage/img'), $filename);
            $post['img_filename']= $filename;
        }

        $post->save();

        return redirect(route('posts.index'))->with('status', 'Post edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        $post->deleted_by = Auth::id();
        $post->save();

        // redirect back to the main view for this controller
        return redirect(route('posts.index'))->with('status', 'Post deleted');
    }
}
