<!-- resources/views/auth/register.blade.php -->
@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Register</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">No. Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Daftar Sebagai</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Penumpang</option>
                                    <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Driver specific fields -->
                    <div id="driver-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label">Jenis Kendaraan</label>
                                    <select class="form-select" id="vehicle_type" name="vehicle_type">
                                        <option value="">Pilih Kendaraan</option>
                                        <option value="motor">Motor</option>
                                        <option value="mobil">Mobil</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="vehicle_plate" class="form-label">Plat Nomor</label>
                                    <input type="text" class="form-control" id="vehicle_plate" name="vehicle_plate">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="license_number" class="form-label">No. SIM</label>
                                    <input type="text" class="form-control" id="license_number" name="license_number">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" 
                                       name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                
                <div class="text-center mt-3">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const driverFields = document.getElementById('driver-fields');
    if (this.value === 'driver') {
        driverFields.style.display = 'block';
    } else {
        driverFields.style.display = 'none';
    }
});
</script>
@endsection