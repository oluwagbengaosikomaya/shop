@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-center mb-4"><i class="fa fa-user me-2 text-danger"></i>Customer Login</h4>

                    @if($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-1">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Remember Me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-muted">Forgot password?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">Login</button>
                        </div>
                    </form>

                    <hr>
                    <div class="text-center small">
                        <p class="mb-1">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                        <a href="{{ route('shop.index') }}" class="text-muted">← Back to Shop</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
