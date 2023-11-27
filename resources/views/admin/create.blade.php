@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add Admin') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="{{ route('admin.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <label for="roles" class="form-label">Roles</label>
                        <ul class="list-group" id="roles">
                            @foreach($roles as $role)
                                <li class="list-group-item">
                                    <input
                                        class="form-check-input me-1"
                                        type="checkbox"
                                        name="roles[]"
                                        id="{{ $role->id }}"
                                        value="{{ $role->id }}"
                                        @if (is_array(old('roles')) && in_array($role->id, old('roles')))
                                            checked
                                        @endif
                                    >
                                    <label for="{{ $role->id }}" class="form-label">{{ $role->name }}</label>
                                </li>
                            @endforeach
                        </ul>
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
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{route('admin.index')}}" class="btn btn-warning">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
