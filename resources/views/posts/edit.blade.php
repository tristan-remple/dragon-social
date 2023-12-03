@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Post') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') ?? $post->title }}">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <input type="text" class="form-control" id="content" name="content" value="{{ old('content') ?? $post->content }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            @if ($post->img_filename)
                                <div class="text-center">
                                    <img src="{{ asset('storage/img/'.$post->img_filename) }}" alt="{{ $post->alt }}" class="mw-100 object-fit-cover">
                                </div>
                            @else
                                <p>This post has no image.</p>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">New Image (Optional)</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="mb-3">
                            <label for="alt" class="form-label">Alt Text (For Image)</label>
                            <input type="text" class="form-control" id="alt" name="alt" value="{{ old('alt') ?? $post->alt }}">
                        </div>
                        <br />
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">Edit</button>
                        <a href="{{route('posts.index')}}" class="btn btn-warning">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
