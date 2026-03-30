@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Admin Users</h4>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
        + Add Admin User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admins as $admin)
        <tr>
            <td>{{ $admin->id }}</td>
            <td>{{ $admin->name }}</td>
            <td>{{ $admin->email }}</td>
            <td>{{ $admin->created_at->format('d M Y') }}</td>
            <td>
                @if($admin->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this admin?')">
                            Delete
                        </button>
                    </form>
                @else
                    <span class="badge bg-secondary">Current User</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
