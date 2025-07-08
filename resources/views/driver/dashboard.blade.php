@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Current Order</h5>
            </div>
            <div class="card-body">
                @if($activeOrder)
                    <div class="alert alert-info">
                        <h6>Order #{{ $activeOrder->id }}</h6>
                        <p>Type: {{ ucfirst($activeOrder->type) }}</p>
                        <p>Status: {{ ucfirst($activeOrder->status) }}</p>
                        <p>Price: Rp {{ number_format($activeOrder->ongkir, 0, ',', '.') }}</p>
                        <a href="{{ route('driver.orders.show', $activeOrder) }}" class="btn btn-sm btn-primary">View Details</a>
                    </div>
                @else
                    <p>No active orders</p>
                    <a href="{{ route('driver.orders.available') }}" class="btn btn-primary">Find Available Orders</a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Recent Completed Orders</h5>
            </div>
            <div class="card-body">
                @if($orderHistory->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Price</th>
                                    <th>Completed At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderHistory as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ ucfirst($order->type) }}</td>
                                    <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                                    <td>{{ $order->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No completed orders yet</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Driver Status</h5>
            </div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="onlineStatus" 
                        {{ Auth::user()->is_online ? 'checked' : '' }}>
                    <label class="form-check-label" for="onlineStatus">
                        {{ Auth::user()->is_online ? 'Online' : 'Offline' }}
                    </label>
                </div>
                <hr>
                <p><strong>Vehicle:</strong> {{ Auth::user()->vehicle_type }} ({{ Auth::user()->vehicle_number }})</p>
                <p><strong>Rating:</strong> 
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round(Auth::user()->rating) ? '-fill text-warning' : '' }}"></i>
                    @endfor
                    ({{ number_format(Auth::user()->rating, 1) }})
                </p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('driver.orders.available') }}" class="btn btn-primary w-100 mb-2">Available Orders</a>
                <a href="{{ route('driver.orders.index') }}" class="btn btn-secondary w-100 mb-2">My Orders</a>
                <a href="{{ route('driver.profile.edit') }}" class="btn btn-outline-primary w-100">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('onlineStatus').addEventListener('change', function() {
        fetch('{{ route("driver.status.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                is_online: this.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const label = document.querySelector('label[for="onlineStatus"]');
                label.textContent = this.checked ? 'Online' : 'Offline';
            }
        });
    });
</script>
@endpush
@endsection