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
    // require users to be signed in to access routes other than index and show
    function __construct() {
        $this->middleware(['auth'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all posts in chronological order
        // return that data with the index view
        // the moderator role is passed in for logic around the delete button
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
        // posts must have a title and content
        // if there's an image uploaded, it has to actually be an image
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => [
                File::image()
            ]
        ]);

        // create a new post
        $newPost = new Post();

        // apply request fields and created by user to the new post
        $newPost->title = $request->title;
        $newPost->content = $request->content;
        if ($request->alt) {
            $newPost->alt = $request->alt;
        }
        $newPost->created_by = Auth::id();

        // if an image was uploaded, rename the file, move it to storage, and save the new filename to the post
        // https://codesource.io/complete-laravel-8-image-upload-tutorial-with-example/
        if($request->file('image')){
            $file= $request->file('image');
            $filename= date('YmdHi').'_'.$file->getClientOriginalName();
            $file-> move(public_path('storage/img'), $filename);
            $newPost['img_filename']= $filename;
        }

        // save the post
        $newPost->save();

        // return the posts index view with a success message
        return redirect(route('posts.index'))->with('status', 'Post added successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // post is put in an array so we can easily reuse the index view
        // lazy way out but it works in this context
        // again, moderator is supplied for delete permission purposes
        $posts = [$post];
        $moderator = Role::where('name', 'Moderator')->first();
        return(view('posts.index', compact(['posts', 'moderator'])));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // while users have to be signed in to get past the middleware
        // i also want the user to be the same one who made the post
        if (Auth::id() === $post->created_by){
            return view('posts/edit', compact('post'));
        } else {
            return redirect(route('posts.index'))->with('status', 'You cannot edit other users\' posts.');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // same validation rules as create
        $request->validate([
            'title' => ['required'],
            'content' => ['required'],
            'image' => [
                File::image()
            ]
        ]);

        // also the same as create, except we're using the post parameter instead of making a new one
        $post->title = $request->title;
        $post->content = $request->content;
        if ($request->alt) {
            $post->alt = $request->alt;
        }
        $post->created_by = Auth::id();

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
        // since this isn't a get route, it can't be accessed unless the button is there
        // so i don't believe we need additional safeguards here

        // delete (soft delete enabled)
        $post->delete();

        // save the id of the user who deleted
        $post->deleted_by = Auth::id();
        $post->save();

        // redirect back to the main view for this controller
        return redirect(route('posts.index'))->with('status', 'Post deleted');
    }
}
