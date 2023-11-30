@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    {{ __('Dashboard') }}
                    @if (Auth::check())
                        <a href="{{ route('posts.create') }}" class="btn btn-success">Create Post</a>
                    @endif
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @foreach ($posts as $post)
                        <div class="card mb-1">
                            <div class="card-header">
                                {{ $post->title }}<br />
                                <small class="text-muted text-end">
                                    {{ $post->creator->name }},
                                    {{ Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                                </small>
                            </div>

                            <div class="card-body">
                                <div class="text-center">
                                    <img src="{{ asset('storage/img/'.$post->img_filename) }}" alt="{{ $post->alt }}" class="mw-100 object-fit-cover">
                                </div>
                                <p class="card-text">{{ $post->content }}</p>

                            </div>
                            <div class="card-body row">
                                <div class="col-sm d-flex">
                                    @if (Auth::check() && $post->creator->id === Auth::user()->id)
                                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning flex-fill">Edit</a>
                                    @endif
                                </div>
                                <form class="col-sm d-flex" method="post" action="{{ route('posts.destroy', $post->id ) }}">
                                    @csrf
                                    @if (Auth::check() && ($post->creator->id === Auth::user()->id || Auth::user()->roles->contains($moderator)))
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger flex-fill">Delete</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
