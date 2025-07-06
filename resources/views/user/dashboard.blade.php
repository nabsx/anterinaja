<!-- resources/views/user/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard Penumpang')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Penumpang</h2>
            <a href="{{ route('user.create-order') }}" class="btn btn-primary">Buat Pesanan Baru</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Layanan Ride</h5>
                <p class="card-text">Pesan ojek untuk antar jemput</p>
                <a href="{{ route('user.create-order') }}?type=ride" class="btn btn-success">Pesan Ride</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h5 class="card-title">Layanan Delivery</h5>
                <p class="card-text">Pesan ojek untuk antar barang</p>
                <a href="{{ route('user.create-order') }}?type=delivery" class="btn btn-warning">Pesan Delivery</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Pesanan Terakhir</h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Jenis</th>
                                <th>Dari</th>
                                <th>Ke</th>
                                <th>Ongkir</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->type == 'ride' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->type) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($order->alamat_jemput, 30) }}</td>
                                <td>{{ Str::limit($order->alamat_tujuan, 30) }}</td>
                                <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">Belum ada pesanan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection