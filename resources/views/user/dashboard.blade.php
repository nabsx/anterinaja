@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Active Order</h5>
            </div>
            <div class="card-body">
                @if($activeOrder)
                    <div class="alert alert-info">
                        <h6>Order #{{ $activeOrder->id }}</h6>
                        <p>Status: {{ ucfirst($activeOrder->status) }}</p>
                        <p>Driver: {{ $activeOrder->driver ? $activeOrder->driver->name : 'Not assigned' }}</p>
                        <a href="{{ route('user.orders.show', $activeOrder) }}" class="btn btn-sm btn-primary">View Details</a>
                    </div>
                @else
                    <p>No active orders</p>
                    <a href="{{ route('user.orders.create') }}" class="btn btn-primary">Create New Order</a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Order History</h5>
            </div>
            <div class="card-body">
                @if($orderHistory->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderHistory as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ ucfirst($order->type) }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No order history</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('user.orders.create') }}" class="btn btn-primary w-100 mb-2">New Order</a>
                <a href="{{ route('user.orders.index') }}" class="btn btn-secondary w-100 mb-2">All Orders</a>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-primary w-100">My Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection