@extends('layouts.app')

@section('title', 'User Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">User Login</div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="{{ route('user.register') }}" class="btn btn-link">Register</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection