@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2>{{ $stats['total_users'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Drivers</h5>
                <h2>{{ $stats['total_drivers'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2>{{ $stats['total_orders'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <h2>Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Recent Orders</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Driver</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->driver ? $order->driver->name : '-' }}</td>
                        <td>{{ ucfirst($order->type) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection