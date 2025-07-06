<!-- resources/views/user/create-order.blade.php -->
@extends('layouts.app')

@section('title', 'Buat Pesanan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Buat Pesanan Baru</h4>
            </div>
            <div class="card-body">
                <form id="orderForm" method="POST" action="{{ route('user.orders.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Jenis Layanan</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Pilih Layanan</option>
                            <option value="ride" {{ old('type', request('type')) == 'ride' ? 'selected' : '' }}>Ride (Antar Jemput)</option>
                            <option value="delivery" {{ old('type', request('type')) == 'delivery' ? 'selected' : '' }}>Delivery (Antar Barang)</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Pickup Location -->
                    <div class="mb-3">
                        <label for="alamat_jemput" class="form-label">Alamat Jemput</label>
                        <input type="text" class="form-control @error('alamat_jemput') is-invalid @enderror" 
                               id="alamat_jemput" name="alamat_jemput" value="{{ old('alamat_jemput') }}" required>
                        <input type="hidden" id="jemput_lat" name="jemput_lat" value="{{ old('jemput_lat') }}">
                        <input type="hidden" id="jemput_lng" name="jemput_lng" value="{{ old('jemput_lng') }}">
                        @error('alamat_jemput')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Destination Location -->
                    <div class="mb-3">
                        <label for="alamat_tujuan" class="form-label">Alamat Tujuan</label>
                        <input type="text" class="form-control @error('alamat_tujuan') is-invalid @enderror" 
                               id="alamat_tujuan" name="alamat_tujuan" value="{{ old('alamat_tujuan') }}" required>
                        <input type="hidden" id="tujuan_lat" name="tujuan_lat" value="{{ old('tujuan_lat') }}">
                        <input type="hidden" id="tujuan_lng" name="tujuan_lng" value="{{ old('tujuan_lng') }}">
                        @error('alamat_tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Delivery specific fields -->
                    <div id="delivery-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receiver_name" class="form-label">Nama Penerima</label>
                                    <input type="text" class="form-control" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receiver_phone" class="form-label">No. HP Penerima</label>
                                    <input type="text" class="form-control" id="receiver_phone" name="receiver_phone" value="{{ old('receiver_phone') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="package_description" class="form-label">Deskripsi Barang</label>
                            <textarea class="form-control" id="package_description" name="package_description" rows="3">{{ old('package_description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Catatan Tambahan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    </div>
                    
                    <!-- Price Estimation -->
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <h6>Estimasi Biaya:</h6>
                            <p id="price-estimation">Pilih lokasi untuk melihat estimasi harga</p>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Peta</h6>
            </div>
            <div class="card-body p-0">
                <div id="map" class="map-container"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let map;
let pickupMarker;
let destinationMarker;
let routeControl;

// Initialize map
function initMap() {
    map = L.map('map').setView([-7.2575, 112.7521], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Click event for map
    map.on('click', function(e) {
        // Implementation for map click
    });
}

// Calculate distance and price
function calculatePrice() {
    const pickupLat = document.getElementById('jemput_lat').value;
    const pickupLng = document.getElementById('jemput_lng').value;
    const destLat = document.getElementById('tujuan_lat').value;
    const destLng = document.getElementById('tujuan_lng').value;
    
    if (pickupLat && pickupLng && destLat && destLng) {
        // Call API to calculate distance and price
        fetch('/api/calculate-price', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                pickup_lat: pickupLat,
                pickup_lng: pickupLng,
                dest_lat: destLat,
                dest_lng: destLng
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('price-estimation').innerHTML = 
                    `<strong>Jarak:</strong> ${data.distance_km} km<br>
                     <strong>Estimasi Harga:</strong> Rp ${data.price.toLocaleString('id-ID')}`;
            }
        });
    }
}

// Show/hide delivery fields
document.getElementById('type').addEventListener('change', function() {
    const deliveryFields = document.getElementById('delivery-fields');
    if (this.value === 'delivery') {
        deliveryFields.style.display = 'block';
    } else {
        deliveryFields.style.display = 'none';
    }
});

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Show delivery fields if type is already selected
    if (document.getElementById('type').value === 'delivery') {
        document.getElementById('delivery-fields').style.display = 'block';
    }
});
</script>
@endpush
@endsection