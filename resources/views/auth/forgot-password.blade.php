@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-center mb-2">Forgot Password</h4>
                    <p class="text-muted text-center small mb-4">Enter your email and we'll send you a reset link.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">Send Reset Link</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="small text-muted">← Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
