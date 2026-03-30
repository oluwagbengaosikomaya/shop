@extends('admin.layouts.app')

@section('content')
<h4 class="mb-3">Add Admin User</h4>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <small class="text-muted">Minimum 6 characters</small>
            </div>

            <button type="submit" class="btn btn-success">Create Admin User</button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
