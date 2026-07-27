@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-5" style="max-width:680px">
    <h4 class="fw-bold mb-4">My Profile</h4>

    {{-- Personal Info --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Personal Information</h6>

            @if($errors->hasAny(['name','email','phone']))
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('user.profile.info') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +234 800 000 0000">
                </div>
                <button type="submit" class="btn btn-danger btn-sm">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="card shadow-sm border-0" id="password">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Change Password</h6>

            @if($errors->hasAny(['current_password','password']))
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('user.profile.password') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger btn-sm">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
