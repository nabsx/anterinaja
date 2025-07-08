@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="text-center py-5">
    <h1 class="display-4">Ojek Online</h1>
    <p class="lead">Ride & Delivery Service</p>
    
    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person"></i> User</h5>
                    <p class="card-text">Order ride or delivery service</p>
                    <a href="{{ route('user.login') }}" class="btn btn-primary">Login as User</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bicycle"></i> Driver</h5>
                    <p class="card-text">Accept and complete orders</p>
                    <a href="{{ route('driver.login') }}" class="btn btn-success">Login as Driver</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-shield-lock"></i> Admin</h5>
                    <p class="card-text">Manage system and users</p>
                    <a href="{{ route('admin.login') }}" class="btn btn-danger">Login as Admin</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection